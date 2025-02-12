# Project Overview

This project is designed to automate one of the internal company processes. The development is still in progress.

## Architecture

- The project follows the **Hexagonal Architecture (Ports and Adapters)** pattern.
- Business logic is separated into **Commands and Queries** to ensure scalability.
- **Apache Kafka with Zookeeper** will be integrated to handle a large number of events reliably and ensure system recoverability.

## Development Environment

- The project runs in a **Docker**-based local environment.
- A **Makefile** is included to simplify execution of essential commands.

## Authentication

- **Keycloak** is used as the authentication provider.
- This allows seamless integration of additional services while maintaining a unified user base.