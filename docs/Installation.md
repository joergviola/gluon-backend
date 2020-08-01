# Installation

The gluon backend is a plugin for the [Laravel](https://laravel.com) PHP framework.
So you start by creating a laravel application, e.g. by using composer like

````
$ composer create-project --prefer-dist laravel/laravel test-app
````

In this application, you now install the gluon backend plugin and make its database schema available for migration:

````
$ cd test-app
$ composer install gluon/gluon-backend
$ php artisan vendor:publish --provider="Gluon\Backend\Providers\GluonServiceProvider" --tag="migrations"
````

Note that, for autorization, this also installs [Laravel Passport](https://laravel.com/docs/7.x/passport).

Now its time to create your database and user:

````
$ mysql -u root -p
mysql> CREATE DATABASE testapp;
mysql> CREATE USER 'testapp'@'localhost' IDENTIFIED BY 'testapp';
mysql> GRANT ALL PRIVILEGES ON testapp.* TO 'testapp'@'localhost';
````

Of course, you have to use stronger credentials outside your development environment!
Now you enter these credentials in `.env`:
````
DB_DATABASE=testapp
DB_USERNAME=testapp
DB_PASSWORD=testapp
````

Now that your laravel application is able to connect to the new database, you can run the database migrations
(If you want to modify the standard table layout, edit the files in `database/migrations`):

````
php artisan migrate
````

In order to initialize passport authorisation, run:

````
php artisan passport:install
````

If you want to store documents in the local file system, initialize their public access symlink:

````
php artisan passport:install
````

At this time, your new gluon application is ready to work.
Create the first client and user by entering this command:

````
php artisan gluon:client
````
