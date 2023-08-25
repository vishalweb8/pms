# Internal Portal

Project built to manage operations and resource management functionalities and integrate it with the laravel front end.

## Install 

Configure the project locally, with the scripts listed below you can install the project and configure database (locally) to perform your tests.

## Install composer by following command : 
```shell
$ composer install
```
## Run below command to install UI packages.

```shell
$  composer require laravel/ui
```
```shell
$  php artisan ui bootstrap
```

Create or take original .env file and store within root directory of repo then follow below commands:

```shell
$ php artisan key:generate
```

- To execute migration :
```shell
$ php artisan migrate
```

- For execute seeders :
```shell
$ php artisan db:seed
```

- To setup storage in the project :
```shell
$ php artisan storage:link
```

Project setup successfully done.

## Note : If any modification done in .env or any files of config folder then make sure to execute below commands for reflect implemented changes
```shell
$ php artisan cache:clear
$ php artisan config:cache
```