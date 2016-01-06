# Start! - Doodle under your control

Start! is an open source clone of the popular doodle.com service. It is in wide use but not everyone
is comfortable sharing what they are up to with a commercial service.
 
This project aims at allowing users to be in control and host their own service.

## License

This project is licensed under the GNU Affero general 

## Getting started

As for now, this project is under development. It is **not yet intendet for end users**. 

You must have [composer](https://getcomposer.org) and [git](https://git-scm.com) installed on your system.
Also for initial setup, it is highly recommended that the php executable is in your PATH.

## How to install

Checkout the project from github `git clone git@github.com:Takuto88/start.git` and change into the cloned repository.
From there, run `composer install` to install all dependencies. 

Then, you need to setup your virtual host. Here is an Apache configuration you may use for
orientation:

```
<VirtualHost *:80>
  ServerName start.dev # Must match your hosts-file name!
  DocumentRoot "/path/to/the/project/public" # Be sure to point to the /public folder in your project!
  
  <Directory /path/to/the/project>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All # Not recommended on production systems
    
    # Apache 2.4 and greater
    Require all granted
    
    # Apache 2.2: Use this instead
    #Order allow,deny
    #allow from all
  </Directory>
  
  # Optional - Use this to use 'development.php' as your config-file.
  # The App will default 'config.php' if APPLICATION_ENV is undefined.
  # SetEnv APPLICATION_ENV development

  # Log files
  CustomLog "logs/silex-access_log" combined
  ErrorLog "logs/silex-error_log"
</VirtualHost>
```

Then, setup your database. If you are comfortable with SQLite, you don't have to change anything. 
If you want to use MySQL you can edit your config file (resources/config/config.php) like this:

```php
<?php 

return array(
        'debug' => false,
        'db.options' => array(
                'driver' => 'pdo_mysql',
                'dbname' => 'yourdb',
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => 'secret',
                'charset' => 'utf8',
                'port' => '3306',
        ),
        'db.orm.options' => array(
                'orm.default_cache' => 'apc'
        )
    
);
```

As you can see, PDO is used. The app should work with any relational database that PDO supports. Run 
`php vendor/bin/doctrine orm:schema-tool:create` in order to create the DB.

Navigate to "http://start.dev" (replace with the name of your VHost) and see what happens.