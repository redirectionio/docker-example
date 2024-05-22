# Using the redirection.io nginx module in a Docker environment

We distribute our nginx module as [Debian/Ubuntu](https://redirection.io/documentation/developer-documentation/nginx-module#debian-and-apt-based-distributions) or [Red Hat](https://redirection.io/documentation/developer-documentation/nginx-module#red-hat-and-rpm-based-distributions) packages, which are available in our repositories.

This example shows how to use the pre-compiled redirection.io nginx module provided in our repositories in a Docker environment.

> [!TIP]
> If your nginx setup is not compatible with our pre-compiled packages, you can [compile the module from sources](../nginx-module-custom/README.md).

## Description

The `service` directory contains two services:

 * **nginx**: a nginx Dockerfile based on the Ubuntu 24.04 image, with nginx and the redirection.io nginx module installed from our apt repository
 * **redirectionio-agent**: a simple Dockerfile to get the agent running

![The redirection.io nginx module](../app/nginx-module.png)

### nginx

This is a standard Ubuntu 24.04 image, with the distribution-provided nginx package, and [libnginx-mod-redirectionio installed from our deb repository, as explained in our documentation](https://redirection.io/documentation/developer-documentation/nginx-module#debian-and-apt-based-distributions).

It defines a single VirtualHost, [for which redirection is enabled](./services/nginx/default.template#L8-L9).

### redirectionio-agent

The agent is installed using our [manual installation](https://redirection.io/documentation/developer-documentation/installation-of-the-agent#manual-installation) instructions. Note that we have enabled a `/var/lib/redirectionio` volume, used to store [redirection.io agent's cache data](https://redirection.io/documentation/developer-documentation/agent-configuration-reference#datadir).
