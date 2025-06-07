#!/bin/bash
set -e

# Function to log messages
log() {
  echo "[$(date +%Y-%m-%d\ %H:%M:%S)] $1"
}

log "Starting Railway deployment for Moodle with iPaymu plugin"

# Adapt port for Railway if needed
if [ ! -z "$PORT" ]; then
  log "Configuring Apache to use PORT: $PORT"
  # Replace Apache port in configuration
  sed -i "s/Listen 8080/Listen $PORT/g" /opt/bitnami/apache/conf/httpd.conf
  sed -i "s/:8080/:$PORT/g" /opt/bitnami/apache/conf/httpd.conf
fi

# Initialize MariaDB if needed
if [ ! -d "/bitnami/mariadb/mysql" ]; then
  log "Initializing MariaDB database..."
  
  # Start MySQL service
  /usr/bin/mysqld_safe --datadir=/bitnami/mariadb &
  
  # Wait for MySQL to start up
  for i in {30..0}; do
    if echo 'SELECT 1' | mysql &> /dev/null; then
      break
    fi
    log "Waiting for MySQL to start..."
    sleep 1
  done
  
  if [ "$i" = 0 ]; then
    log "MySQL failed to start"
    exit 1
  fi

  # Create database and user for Moodle
  log "Creating MySQL database and user for Moodle..."
  mysql -e "CREATE DATABASE IF NOT EXISTS \`$MOODLE_DATABASE_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  mysql -e "CREATE USER IF NOT EXISTS '$MOODLE_DATABASE_USER'@'localhost' IDENTIFIED BY '$MOODLE_DATABASE_PASSWORD';"
  mysql -e "GRANT ALL PRIVILEGES ON \`$MOODLE_DATABASE_NAME\`.* TO '$MOODLE_DATABASE_USER'@'localhost';"
  mysql -e "FLUSH PRIVILEGES;"
  
  log "Database initialization complete"
  
  # Stop MySQL service to let supervisord manage it
  mysqladmin -u root shutdown
else
  log "MariaDB data directory already exists, skipping initialization"
fi

# Update Moodle configuration if it exists
if [ -f "/opt/bitnami/moodle/config.php" ]; then
  log "Updating Moodle configuration for Railway..."
  # Update database connection settings
  sed -i "s/\$CFG->dbhost = .*/\$CFG->dbhost = 'localhost';/g" /opt/bitnami/moodle/config.php
  sed -i "s/\$CFG->dbname = .*/\$CFG->dbname = '$MOODLE_DATABASE_NAME';/g" /opt/bitnami/moodle/config.php
  sed -i "s/\$CFG->dbuser = .*/\$CFG->dbuser = '$MOODLE_DATABASE_USER';/g" /opt/bitnami/moodle/config.php
  sed -i "s/\$CFG->dbpass = .*/\$CFG->dbpass = '$MOODLE_DATABASE_PASSWORD';/g" /opt/bitnami/moodle/config.php
fi

# Start supervisord to manage all services
log "Starting all services using supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
