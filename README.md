# redirection.io Docker examples

It is quite common to use Docker in development or production environments. As
this has been requested by several of our users, we have set up different examples
to show how to integrate redirection.io in various Docker setups.

## Usage

 * clone this repository
    ```sh
    git clone https://github.com/redirectionio/docker-example.git
    cd docker-example
    ```
 * create an account and a project on [redirection.io](https://redirection.io), retrieve your `project key` in [redirection.io's manager](https://redirection.io/manager) and copy it
 * copy the `.env.dist` file to `.env` and paste your `project key` in it:
    ```sh
    cp .env.dist .env
    ```
 * choose one of the docker layouts (see below), and:
    ```sh
    cd <your choice>
    ```
 * build the infrastructure:
    ```sh
    docker compose build
    ```
 * run it:
    ```sh
    docker compose up -d
    ```
 * open your browser and go to [http://localhost:8080/](http://localhost:8080/)

## Available Docker layouts

 * [**agent-as-reverse-proxy**](./agent-as-reverse-proxy/README.md): the redirection.io agent, installed from our repository, is used as a reverse proxy. This is the most simple and recommended setup.
 * [**apache-module**](./apache-module/README.md): a simple Apache setup, with redirection.io module installed from our apt repository
 * [**apache-module-custom**](./apache-module-custom/README.md): an Apache setup with the redirection.io module compiled from sources
 * [**nginx-module**](./nginx-module/README.md): a simple nginx setup, with redirection.io module installed from our apt repository
 * [**nginx-module-custom**](./nginx-module-custom/README.md): a nginx setup with the redirection.io module compiled from sources

## Help and troubleshooting

We do not offer major support for this Docker example. However, feel free to
contact us or open an issue if you have any question.

##  License

This code is licensed under the MIT License - see the [LICENSE](./LICENSE.md)
file for details.
