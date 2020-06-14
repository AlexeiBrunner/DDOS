INSTALLATION
------------
If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

Configure autoloading with Composer:

~~~
composer dump-autoload
~~~

Set up a database connection in [DB.php](src/databases/DB.php)

Need to complete migrations:

~~~
php migration\create_banned_ips_table.php
~~~
