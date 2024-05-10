# IOT Monitoring Application

A real-time monitoring dashboard for IoT modules.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images.
3. Run `docker compose up -d --wait` to set up and launch the project.
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334).
5. Open `https://localhost:8025` to access the Mailpit interface.
6. Open `https://localhost:8080` to access the PHPMyAdmin interface.
7. Run `docker compose down --remove-orphans` to stop the Docker containers.
