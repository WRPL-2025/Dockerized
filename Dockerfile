# Dockerfile to build a Moodle image with the iPaymu enrolment plugin
# Use the official Moodle Apache image as base
FROM bitnami/moodle:latest

# Default environment variables for Railway deployments
ENV MOODLE_DATABASE_HOST=mariadb \
    MOODLE_DATABASE_NAME=bitnami_moodle \
    MOODLE_DATABASE_USER=root \
    MOODLE_DATABASE_PASSWORD=Root123#! \
    ALLOW_EMPTY_PASSWORD=yes \
    MOODLE_USERNAME=admin \
    MOODLE_PASSWORD=Admin123! \
    MOODLE_EMAIL=you@example.com \
    MOODLE_SITE_NAME="My Moodle Site" \
    BITNAMI_DEBUG=true

# Copy the iPaymu plugin into Moodle's enrol directory
COPY ipaymu /opt/bitnami/moodle/enrol/ipaymu

# Ensure correct permissions
RUN chown -R 1001:1001 /opt/bitnami/moodle/enrol/ipaymu

# Expose web server port
EXPOSE 8080
