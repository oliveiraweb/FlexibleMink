# FlexibleMink
Mink Extensions for Commonly Used Assertions and Object Storage

## Dependancies
This project is built with docker and uses Behat and PHPUnit for testing. You will need to install the following:
- [Docker Engine](https://docs.docker.com/engine/installation/)
- [PHP](http://php.net/manual/en/install.php)
- [Kitematic](https://kitematic.com/) (Optional) 

The Behat configuration is set up to run against a host named `dockermachine.local`. You can either create an entry in your `/etc/hosts` file for this, or you can directly edit the `behat.yml` file in the project root and change the `wd_host` property from `dockermachine.local` to the IP.

Refer to the appropriate section below to retrieve your container's IP.

### Docker Toolbox
```
> docker-machine ip $DOCKER_MACHINE_NAME
```

### Docker for Mac
```
> docker ps | grep httpd
```
You'll be able to see the IP under ports (it's usually 0.0.0.0).

## Setup
FlexibleMink comes packaged with some basic tests which double as exampels and tests of the various contexts implemented. The tests are run on Google Chrome through Selenium against an Apache web server.

To setup the test environment, run the following commands in the project root directory.
```
> bin/containers up
> bin/init_project
```

This will spin up the Selenium and Apache docker containers and the install the node and composer dependancies into the project directory.

## Usage
Once the setup is complete, usage is quite simple. Run the following in the project root diretory.
```
> bin/phpunit
> bin/behat
```

## Debugging
Selenium is configured for VNC on the default port (5900). If you have a VNC client, simply point it to ```vnc://dockermachine.local``` (or the container's ip) on port 5900. The password is the default password provided by Selenium, which is `secret` (as documented on [Selenium](https://github.com/SeleniumHQ/docker-selenium)).
