# FlexibleMink
Mink Extensions for Commonly Used Assertions and Object Storage

## Setup
FlexibleMink comes packaged with some basic tests which double as exampels and tests of the various contexts implemented.

You will need npm and node installed on your machine to run the `init` script. You will also need a local webserver (we recommend python's SimpleHTTPServer, but you'll need to install python for that). The simplest way to do get these dependancies is to use Homebrew (osX).

To get up and running, simply run the following in the root directory of the project.
```
bin/init_project
```

This will download and install composer dependancies as well as node dependancies and the selenium server.

## Usage
Usage is quite simple:
* start the selenium server
* start a local web server
* run the behat tests

This can be accomplished with the following. The web server and selenium output to console, so you may want to do these in separate terminal sessions.
```
> bin/start-selenium
> cd web/ && python -m SimpleHTTPServer 8080
> bin/behat
```
