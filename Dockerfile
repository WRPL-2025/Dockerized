# Dockerfile to build a single-container Moodle image with the iPaymu enrolment plugin for Railway
# Use the official Moodle Apache image as base
FROM bitnami/moodle:latest

# Install MariaDB and required packages
USER root
RUN install_packages mariadb-server mariadb-client supervisor procps curl

# Define build arguments for sensitive data
ARG MYSQL_PASSWORD=password
ARG MOODLE_ADMIN_PASSWORD=Admin123!

# Default environment variables for Railway deployments
ENV MOODLE_DATABASE_HOST=localhost \
    MOODLE_DATABASE_PORT=3306 \
    MOODLE_DATABASE_NAME=moodle \
    MOODLE_DATABASE_USER=moodle \
    ALLOW_EMPTY_PASSWORD=no \
    MOODLE_USERNAME=admin \
    MOODLE_EMAIL=you@example.com \
    MOODLE_SITE_NAME="My Moodle Site" \
    BITNAMI_DEBUG=false \
    PORT=8080 \
    RAILWAY_DEPLOYMENT=true

# Set sensitive environment variables via arguments
ENV MOODLE_DATABASE_PASSWORD=$MYSQL_PASSWORD \
    MOODLE_PASSWORD=$MOODLE_ADMIN_PASSWORD

# Copy the iPaymu plugin into Moodle's enrol directory
COPY ipaymu /opt/bitnami/moodle/enrol/ipaymu

# Create directory for MariaDB data and socket directory
RUN mkdir -p /bitnami/mariadb && \
    chown -R 1001:1001 /bitnami/mariadb && \
    mkdir -p /docker-entrypoint-initdb.d && \
    chown -R 1001:1001 /docker-entrypoint-initdb.d && \
    mkdir -p /run/mysqld && \
    chown -R 1001:1001 /run/mysqld && \
    chmod 1777 /run/mysqld && \
    chmod -R 775 /bitnami/mariadb

# Configure supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy the startup script
COPY start.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/start.sh && \
    chown -R 1001:1001 /opt/bitnami/moodle/enrol/ipaymu

# Expose port - this will be overridden by Railway's PORT environment variable
EXPOSE 8080

# Switch back to non-root user
USER 1001

# Set the startup command
CMD ["/usr/local/bin/start.sh"]
