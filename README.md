wp-shinycode
============

### Desc:

**WP Shiny code** is another **syntax highlighting** plugin for WordPress. It uses [GeSHi](http://qbnz.com/highlighter/) library and of course, is server-side.

### Fucer Cahier des Charges:

## Construction du plugin et méthodo

Pour le moment je n'ai aucune idée de comment sera construit le plugin. Je verrais lorsque j'aurais commencé à mettre les mains dans le php. 

L'arborécense du dossier sera grosso merdo la suivante : 

* wp-shinycode
    - includes
        + admin
            * wp-shinycode-admin.php (all admin stuff: menu, page, etc...)
            * wp-shinycode-options.php (all plugin options: fields, etc... )
        + css
            * themes
                - wp-shinycode-themename.css
                - wp-shinycode-themename.css
                - wp-shinycode-themename.css
                - wp-shinycode-themename.css
                - wp-shinycode-themename.css
            * wp-shinycode-global.css (admin and front-end style)
        + images
            * logo.png
            * some icons (maybe)
        + js
            * some js files. Don't know yet
        + lib
            * geshi
            * geshi.php
        + wp-shinycode-class.php (plugin classes)
    - languages
        + wp-shinycode-fr_FR.mo
        + wp-shinycode-fr_FR.po
        + wp-shinycode.pot
    - readme.txt
    - wp-shinycode.php
    - wp-shinycode-install.php
    - wp-shinycode-uninstall.php


### Todo: 

Soon..

