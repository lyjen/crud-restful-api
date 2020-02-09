# RESTFUL API | JEN EXAM

## Structure

```bash
# Access API by adding /api after the URL + the module
# Example
http://shop.exam.com/api/products
http://shop.exam.com/api/orders
http://shop.exam.com/api/orders/item/{id}
````
I created a folder "API" in Controllers folder to make alone APIs.

## Libraries
I used the Laravel framework with passport in order to speed up the creation of API.

## Installation and Testing
````bash
git clone https://github.com/lyjen/crud-restful-api

# Move inside the folder name
cd project-name

# Install dependencies
composer install

# Generate an app encryption key
php artisan key:generate

# Create database and update env file
CREATE DATABASE database_name CHARACTER SET utf8 COLLATE utf8_general_ci;

# Make sure to update below
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop
DB_USERNAME=root
DB_PASSWORD=

# Update APP_URL 
APP_URL=http://shop.exam.com

## Windows Setup by localhost
http://localhost/foldername

# or by virtualhost in /xampp/apache/conf/extra/httpd-vhosts.conf
<VirtualHost *>
  # set path on your project folder
  DocumentRoot "C:/xampp/htdocs/foldername/public"
  ServerName shop.exam.com
  DirectoryIndex index.php
  <Directory "C:/xampp/htdocs/foldername/public">
    AllowOverride All
    Allow from All
  </Directory>
</VirtualHost>

# Update hosts in /Windows/System32/drivers/etc/hosts
127.0.0.1   shop.exam.com

# Migrate database table
php artisan migrate

# Run using below command
php artisan serve

````

## Additional

The requirements of the API should allow the Creation, Retrieval, Updating and Deletion of products. I added a category and status for the products. Since, I want to check the available stock and status of the products. The only way I can think is to have a simple ordering. I added an order status, on which if the status is Complete that's the time we have to deduct the item quantity to product quantity to get the available stocks and to determine the status if the product is Out of Stock or not.

## Like to Add
If I have time I would like to manage the error handling. I would like to display the data or results completely as I'm seeing the frontend developer's request or side. Most of all, I would like to add the UI.

## Comments
I hope you will consider this simple representation of RESTful API CRUD. I didn't add the update for item as I'm running out of time. I also didn't able to manage the display of all datas as you will notice some values are null. Hope to hear from you. Many thanks :)
