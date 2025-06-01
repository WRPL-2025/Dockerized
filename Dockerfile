# Dockerfile to build a Moodle image with the iPaymu enrolment plugin
# Use the official Moodle Apache image as base
FROM bitnami/moodle:latest

# Copy the iPaymu plugin into Moodle's enrol directory
COPY ipaymu /opt/bitnami/moodle/enrol/ipaymu

# Ensure correct permissions
RUN chown -R 1001:1001 /opt/bitnami/moodle/enrol/ipaymu

# Expose web server port
EXPOSE 8080
