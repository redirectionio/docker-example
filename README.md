# redirection.io nginx module Docker example

It is quite common to use Docker in development or production environments. As this has been requested by several of our users, we have set up a short example of a Docker stack for the redirection.io agent and nginx modules.

## Usage

 * clone this repository
	```sh
	git clone https://github.com/redirectionio/docker-example.git
	cd docker-example
	```
 * create an account and a project on [redirection.io](https://redirection.io), retrieve your `project key` in [redirection.io's manager](https://redirection.io/manager) and [copy it in the nginx configuration file](./services/nginx/etc/nginx/nginx.conf#L36)
	```nginx
	redirectionio_project_key PUT HERE YOUR PROJECT KEY;
	```
   The `project key` can be found on the "Instances" screen of your project: simply click on "Setup on your infrastructure".
 * build the infrastructure:
	```sh
	docker-compose build
	```
 * run it:
	```sh
	docker-compose up -d
	```

Head to [http://localhost:8080/](http://localhost:8080/).

## Explanations

The `service` directory contains two services:

 * **redirectionio-agent**: a simple Dockerfile to get the agent running
 * **nginx**: a nginx Dockerfile based on the official nginx images, with all the directives to get the redirection.io nginx module built and loaded

### redirectionio-agent

The agent is installed using our [manual installation](https://redirection.io/documentation/developer-documentation/installation-of-the-agent#manual-installation) instructions. Note that we have enabled a `/var/lib/redirectionio` volume, used to store [redirection.io agent's cache data](https://redirection.io/documentation/developer-documentation/agent-configuration-reference#datadir).

### nginx

Nginx dynamic modules require binary compatibility to be properly loaded, which means that they have to be compiled with the exact same configuration directives like your `nginx` binary.

redirection.io offers APT and RPM repositories, with many versions of `libnginx-mod-redirectionio` to match classical distribution nginx packages. However, should your nginx install vary from these traditional layouts, you will be forced to compile our nginx module yourself, to match your own nginx version.

This is what the [nginx Dockerfile](./services/nginx/Dockerfile) achieves. Basically:
 * it downloads the nginx sources in the same version like the installed `nginx` binary
 * it downloads and build the libredirectionio
 * it downloads the redirection.io nginx module
 * it builds this module with the same configure arguments the installed `nginx` binary was configured
 * it moves the build module in the right folder
 * it [loads the module](./services/nginx/etc/nginx/nginx.conf#L8) in the nginx configuration
 * it [enables redirection.io for the server](./services/nginx/etc/nginx/nginx.conf#L35-36)

## Help and troubleshooting

We do not offer major support for this Docker example. However, feel free to contact us or open an issue if you have any question.

##  License

This code is licensed under the MIT License - see the  [LICENSE](./LICENSE.md)  file for details.
