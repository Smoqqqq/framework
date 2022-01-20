# FRAMEWORK

## Install

cd functions/
git clone https://github.com/scssphp/scssphp.git  
git clone https://github.com/matthiasmullie/minify.git

changer la ligne 190 du fichier functions/minify/src/JS.php en:

    return str_replace("\n", ";", $content);