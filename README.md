# FlexibleMink
Mink Extensions for Commonly Used Assertions and Object Storage

## Dependancies
This project is built with docker and uses Behat and PHPUnit for testing. You will need to install the following:
- [Docker Engine](https://docs.docker.com/engine/installation/)
- [PHP](http://php.net/manual/en/install.php)
- [Kitematic](https://kitematic.com/) (Optional) 

The Behat configuration is set up to run against a host named `dockermachine.local`. You can use the the following command to add it to your hosts file.
```
> echo -e "\n$(docker-machine ip $DOCKER_MACHINE_NAME) dockermachine.local\n" | sudo tee -a /etc/hosts
```

If you don't want to modify the hosts file, you can edit the `/behat.yml` file in the project root and change the IP addresses there to point directly to the docker machine. You can retrieve the IP of your docker machine with the following command.
```
> docker-machine ip $DOCKER_MACHINE_NAME
```

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
Selenium is configured for VNC on the default port (5900). If you have a VNC client, simply point it to ```http://dockermachine.local``` (or the ```docker-machine ip```) on port 5900. The password is the default password provided by Selenium, which is `secret` (as documented on [Selenium](https://github.com/SeleniumHQ/docker-selenium)).
