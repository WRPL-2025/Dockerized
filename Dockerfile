# Dockerfile to build a Moodle image with the iPaymu enrolment plugin
# Use the official Moodle Apache image as base
FROM bitnami/moodle:latest

# Default environment variables for Railway deployments
ENV MOODLE_DATABASE_HOST=${MYSQL_HOST} \
    MOODLE_DATABASE_PORT=${MYSQL_PORT} \
    MOODLE_DATABASE_NAME=${MYSQL_DATABASE} \
    MOODLE_DATABASE_USER=${MYSQL_USER} \
    MOODLE_DATABASE_PASSWORD=${MYSQL_PASSWORD} \
    ALLOW_EMPTY_PASSWORD=no \
    MOODLE_USERNAME=${MOODLE_ADMIN_USERNAME:-admin} \
    MOODLE_PASSWORD=${MOODLE_ADMIN_PASSWORD:-Admin123!} \
    MOODLE_EMAIL=${MOODLE_ADMIN_EMAIL:-you@example.com} \
    MOODLE_SITE_NAME=${MOODLE_SITE_NAME:-"My Moodle Site"} \
    BITNAMI_DEBUG=${BITNAMI_DEBUG:-false}

# Copy the iPaymu plugin into Moodle's enrol directory
COPY ipaymu /opt/bitnami/moodle/enrol/ipaymu

# Ensure correct permissions
RUN chown -R 1001:1001 /opt/bitnami/moodle/enrol/ipaymu

# Expose web server port
EXPOSE 8080
