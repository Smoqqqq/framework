# FRAMEWORK

## Install

cd _bin/dev
git clone https://github.com/scssphp/scssphp.git  
git clone https://github.com/matthiasmullie/minify.git

changer la ligne 190 du fichier _bin/dev/minify/src/JS.php en:

    return str_replace("\n", ";", $content);


## Functionalities

### Pages
By default, templates are in the "templates" folder  
You can configure the folder in .env at  

    TEMPLATES_FOLDER

#### Routing
For each page you create in the template folder, a route is created, with the exact same path and name ex:  
I created a file :

    templates/doc/home.php

I can now access it at myurl.com/doc/home

#### Meta data
in every page, add this to the first lines for meta data :

    title: your title;
    description: your description;
    ###

#### Optionnals tags are:

    url: myUrl
Don't add the first "/"
the url can be ommited, it will default to the path + filename of the file

### Error logging
Error logging is automated, errors are saved to the "var/logs/php_error.log" file by default, this path can be configured by setting the "ERROR_LOG" environment variable

### SCSS Compilation
[SCSSPHP](https://github.com/scssphp/scssphp) is used ad the SCSS compiler.  
You can configure the watch & compile path in .env at

    SCSS_WATCH_PATH
    SCSS_COMPILE_PATH

### JS Minifying
[Minify](https://github.com/matthiasmullie/minify) is used ad the JS Minifier  
You can configure the watch & compile path in .env at

    JS_WATCH_PATH
    JS_COMPILE_PATH