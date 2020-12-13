# Prometheus Demo with PHP exporter

This is a simple codebase, more like a prove of concept of how to instrument your PHP application to be scraped by prometheus

This demo was intended to be present in Darkmira Tour PHP2020 online conference.

All php logic is in demo.php

For spin up our instances we are running docker containers with docker-compose

## Running

Just run

```bash
docker-compose up -d
```

And if you are using Mac

```bash
open http://localhost:8888/
```

Or, if using Linux:

```bash
xdg-open http://example.com
```

## Stopping

```bash
docker-compose down -v
```