# FRAMEWORK

Projet personnel de création d'un framework MVC "Symfony like"

## Install

```
composer install
```
to install dependecies ([https://getcomposer.org/](https://getcomposer.org/))


## Functionalities

### Pages
By default, templates are in the "templates" folder  
You can configure the folder in .env at  

```
TEMPLATES_FOLDER
```
   
they are implemented using
### Twig
Twig is the default templating engine implemented in CascadIO.   
You can render any twig file in a controller using 

```
$this->render("path/to/file.html.twig");
```

#### Routing
Routing is implemented using a Symfony like doctrine annotation.
Ex: 

```
/**
 * @Route(route="/home", name="app_homepage")
 */
```

### Error logging
Error logging is automated.
Errors are saved to the "var/logs/php_error.log" file by default, thought this path can be configured by setting the "ERROR_LOG" environment variable

<!-- ### SCSS Compilation
[SCSSPHP](https://github.com/scssphp/scssphp) is used ad the SCSS compiler.  
You can configure the watch & compile path in .env at

```
SCSS_WATCH_PATH
SCSS_COMPILE_PATH
```

### JS Minifying
[Minify](https://github.com/matthiasmullie/minify) is used ad the JS Minifier  
You can configure the watch & compile path in .env at

```
JS_WATCH_PATH
JS_COMPILE_PATH
``` -->
