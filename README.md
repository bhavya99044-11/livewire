
# 🍔 Food & Grocery Delivery Project

This is Laravel-based food delivery platform where vendors and sellere sell and buy food items and other groceries.

## Prerequisites

Before getting started, make sure you have the following installed:

  - Php >= 8.x
  - Composer
  - Laravel = 12
  - Npm and node >= 23.x

## 📥 Installation

 Install Php and npm packages

 ```bash
 Composer Install
 Npm Install
 ```

## Environment setup

Copy .env.example to .env and configure your database and environment settings.

```bash
php artisan key:generate
```


## Run following commands

 After project setup run below laravel commands

 ```bash
 php artisan migrate(For database table generation)
 php artisan db:seed(To insert seeder data into database)
 php artisan storage:link(TO link public directory with storage)
 ```

 ## Running the Server

 Once the dependencies are installed, you can start the server:

```bash
php artisan serve
npm run dev
```

This will start a local server at http://localhost:8000 where you can preview your site.

