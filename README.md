# IOT Monitoring Application

A real-time monitoring dashboard for IoT modules.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images.
3. Run `docker compose up -d --wait` to set up and launch the project.
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334).
5. Open `http://localhost:8025` to access the Mailpit interface.
6. Open `http://localhost:8080` to access the PHPMyAdmin interface. (credentials in .env file)
7. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Features

-   **Real-time Monitoring**: View IoT module data in real-time for quick insights and decision-making.
-   **Dashboard Interface**: Interactive dashboard for visualizing module data and sensor status with interactive charts for in-depth analysis.
-   **Alerts and Notifications**: Receive alerts and notifications for critical events or anomalies detected by the IoT modules.
-   **Module Customization**: Ability to add and customize modules to track specific data points or devices.
-   **Historical Data Analysis**: Analyze historical data trends to identify potential issues or optimization opportunities.

## Key Libraries

-   **Symfony**: Full-stack PHP framework for building web applications efficiently.
-   **React**: JavaScript library for building user interfaces.
-   **Chart.js**: JavaScript charting library for creating interactive charts and graphs.
-   **Webpack Encore**: Simplified Webpack setup for Symfony applications.
-   **Stimulus**: Bundle for building interactive web applications.
-   **Turbo**: Bundle for speeding up navigation and page loads in web applications.
-   **Tailwind CSS**: Utility-first CSS framework for building customizable user interfaces.

## Usage

1. Visit the IOT Monitoring Application.
2. Set up Docker Compose and launch the project.
3. Access the real-time monitoring dashboard and explore sensor data.
4. Stay informed with alerts and notifications for critical events.
5. Customize the dashboard by adding and configuring modules to track specific data points or devices.
6. Analyze historical data trends to optimize IoT module performance.

## Contribute and Report Issues

If you encounter any bugs or have suggestions for improvements, please open an issue on GitHub.
