# redirection.io nginx module Docker example

It is quite common to use Docker in development or production environments. As
this has been requested by several of our users, we have set up a short example
of a Docker stack for the redirection.io agent and nginx module.

## Usage

 * clone this repository
    ```sh
    git clone https://github.com/redirectionio/docker-example.git
    cd docker-example
    ```
 * create an account and a project on [redirection.io](https://redirection.io), retrieve your `project key` in [redirection.io's manager](https://redirection.io/manager) and copy it in the nginx configuration file:
   * [for the apt-installed nginx module version](./services/nginx/etc/nginx/sites-enabled/default#L9)
   * [for the compiled nginx module version](./services/nginx-compiled/etc/nginx/nginx.conf#L35)
    ```nginx
    redirectionio_project_key PUT HERE YOUR PROJECT KEY;
    ```
   The `project key` can be found on the "Instances" screen of your project:
   simply click on "Setup on your infrastructure".
 * build the infrastructure:
    ```sh
    docker-compose build
    ```
 * run it:
    ```sh
    docker-compose up -d
    ```

Head to:
 * [http://localhost:8080/](http://localhost:8080/) to use a nginx module installed from our packages repository.
 * [http://localhost:8081/](http://localhost:8081/) to use a nginx module compiled during the install.

## Explanations

The `service` directory contains three services:

 * **redirectionio-agent**: a simple Dockerfile to get the agent running
 * **nginx**: a nginx Dockerfile based on the Ubuntu 20.04 image, with nginx and
   the redirection.io nginx module installed from our apt repository
 * **nginx-compiled**: a nginx Dockerfile based on the official nginx image,
   with all the directives to get the redirection.io nginx module built and
   loaded

Depending on your install requirements, the version of nginx that you are using
and other specific nginx modules that you want to use, you can either use the
`nginx` or the `nginx-compiled` services as examples.

### redirectionio-agent

The agent is installed using our [manual installation](https://redirection.io/documentation/developer-documentation/installation-of-the-agent#manual-installation) instructions. Note that we have enabled a `/var/lib/redirectionio` volume, used to store [redirection.io agent's cache data](https://redirection.io/documentation/developer-documentation/agent-configuration-reference#datadir).

### nginx

This is a standard Ubuntu 20.04 image, with the distribution-provided nginx package, and [libnginx-mod-redirectionio installed from our deb repository, as explained in our documentation](https://redirection.io/documentation/developer-documentation/nginx-module#debian-and-apt-based-distributions).

It defines a single VirtualHost, [for which redirection is enabled](./services/nginx/etc/nginx/sites-enabled/default#L8-L9).

### nginx-compiled

Nginx dynamic modules require binary compatibility to be properly loaded, which
means that they have to be compiled with the exact same configuration directives
like your `nginx` binary.

redirection.io offers APT and RPM repositories, with many versions of
`libnginx-mod-redirectionio` to match classical distribution nginx packages.
However, should your nginx install vary from these traditional layouts, you will
be forced to compile our nginx module yourself, to match your own nginx version.

This is what the [nginx Dockerfile](./services/nginx-compiled/Dockerfile) achieves. Basically:
 * it downloads the nginx sources in the same version like the installed `nginx` binary
 * it downloads and build the libredirectionio
 * it downloads the redirection.io nginx module
 * it builds this module with the same configure arguments the installed `nginx` binary was configured
 * it moves the build module in the right folder
 * it [loads the module](./services/nginx-compiled/etc/nginx/nginx.conf#L7) in the nginx configuration
 * it [enables redirection.io for the server](./services/nginx-compiled/etc/nginx/nginx.conf#L34-L35)

## Help and troubleshooting

We do not offer major support for this Docker example. However, feel free to
contact us or open an issue if you have any question.

##  License

This code is licensed under the MIT License - see the [LICENSE](./LICENSE.md)
file for details.
