# Moodle iPaymu Docker Image

A Docker image for [Moodle](https://moodle.org/) with the iPaymu enrolment plugin pre-installed. This image extends the official `bitnami/moodle` container and enables seamless integration of the iPaymu payment gateway for course enrolments.

## Features
- Based on `bitnami/moodle:latest` for a secure, production-ready Moodle setup.
- Includes the `enrol/ipaymu` plugin under `/opt/bitnami/moodle/enrol/ipaymu`.
- Configurable via environment variables for database connection and site setup.
- Persists data using Docker volumes: Moodle data and Apache logs.
- Exposes port `8080` for the Moodle web interface.

## Supported tags
- `latest` : Built from `bitnami/moodle:latest` with iPaymu plugin installed.

## Usage
### Docker run
```bash
docker run -d \
  --name moodle-ipaymu \
  -p 8081:8080 \
  -e MOODLE_DATABASE_HOST=<db-host> \
  -e MOODLE_DATABASE_NAME=<db-name> \
  -e MOODLE_DATABASE_USER=<db-user> \
  -e MOODLE_DATABASE_PASSWORD=<db-password> \
  -e MOODLE_USERNAME=<admin-user> \
  -e MOODLE_PASSWORD=<admin-password> \
  -e MOODLE_EMAIL=<admin-email> \
  -e MOODLE_SITE_NAME=<site-name> \
  bitnami/moodle-ipaymu:latest
```

### Docker Compose
```yaml
version: '3.8'
services:
  mariadb:
    image: bitnami/mariadb:latest
    environment:
      - ALLOW_EMPTY_PASSWORD=yes
      - MARIADB_DATABASE=bitnami_moodle
    volumes:
      - mariadb_data:/bitnami/mariadb

  moodle:
    image: moodle-ipaymu:latest
    ports:
      - "8081:8080"
    environment:
      - MOODLE_DATABASE_HOST=mariadb
      - MOODLE_DATABASE_NAME=bitnami_moodle
      - MOODLE_DATABASE_USER=root
      - MOODLE_DATABASE_PASSWORD=
      - ALLOW_EMPTY_PASSWORD=yes
      - MOODLE_USERNAME=admin
      - MOODLE_PASSWORD=Admin123!
      - MOODLE_EMAIL=you@example.com
      - MOODLE_SITE_NAME=My Moodle Site
      - BITNAMI_DEBUG=true
    volumes:
      - moodle_data:/bitnami/moodle
      - apache_data:/bitnami/apache
    depends_on:
      - mariadb

volumes:
  mariadb_data:
  moodle_data:
  apache_data:
```

## Environment Variables
| Variable                   | Description                                    | Default |
|----------------------------|------------------------------------------------|---------|
| `MOODLE_DATABASE_HOST`     | Hostname of the MariaDB server                 | -       |
| `MOODLE_DATABASE_NAME`     | Name of the Moodle database                    | -       |
| `MOODLE_DATABASE_USER`     | Moodle database user                           | -       |
| `MOODLE_DATABASE_PASSWORD` | Password for the database user                 | -       |
| `ALLOW_EMPTY_PASSWORD`     | Allow empty password for database (`yes`/`no`) | `no`    |
| `MOODLE_USERNAME`          | Admin username                                 | `admin` |
| `MOODLE_PASSWORD`          | Admin password (min 8 chars, letters, symbols) | -       |
| `MOODLE_EMAIL`             | Admin email address                            | -       |
| `MOODLE_SITE_NAME`         | Name of the Moodle site                        | -       |
| `BITNAMI_DEBUG`            | Enable debug logs (`true`/`false`)             | `false` |

## Volumes
- `/bitnami/moodle` — Moodle data and application files
- `/bitnami/apache` — Apache configuration and logs

## Exposed Ports
- `8080` — Moodle web interface

For more information about the iPaymu plugin, refer to the [ipaymu documentation](https://your-plugin-docs-url.example).
