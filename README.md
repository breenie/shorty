# My Silex Skeleton 

A slightly modified silex skeleton based on the [Official Silex Skeleton](https://github.com/silexphp/Silex-Skeleton). 
including various things to make my IDE play nicely and to ease development. Including:

* Built in bootstrap css
* Built PHP server console command
* Renamed templates (template.html => template.html.twig)
 
## Installation

Using the same installation method as the official skeleton:

```sh
$ composer create-project kurl/silex-skeleton path/to/install
```

Composer will create a new Silex project under the path/to/install directory.

## Browsing the Demo Application

To see a real-live Silex page in action, start the PHP built-in web server with command:

```sh
$ cd path/to/install
$ app/console server:run
```

Then, browse to [http://localhost:8000/](http://localhost:8000/).

### That was it!