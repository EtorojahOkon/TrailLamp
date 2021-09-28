# TrailLamp

## Introduction
TrailLamp is a lightweight, easy-to-use Php MVC framework that can be used to build web applications and REST APIs.

## Installation
Clone this directory and rename and place in your project directory

## .env Parameters
**APP_KEY**: A unique application identifier

**APP_URL**: Main server url of your application with no ending slash example http://TrailLamp.test or https://yoursite.com

**APP_ENC_KEY**: A string used for encryption and decryption purposes

**APP_NAME**: Your application/API name

**APP_VERSION**: Your application or API version

**MYSQLI_HOST**: Your SQL host example localhost

**MYSQLI_USER**: Your SQL username, default is root

**MYSQLI_PASSWORD**: Your SQL password

**MYSQLI_DATABASE**: The database name 

## Usage
First set environment variables in .env file.
Then  point to the appropriate url on your browser or on your cmd or terminal, run the following command
```php
  php index.php
```

TrailLamp needs a virtual host to run. For local development, Laragon can be used to develop applications with TrailLamp

## Routing
All routes are set in the routes.php file.
A simple route can be written as follows.
```php
  $routes->get("/", "Controller@Main");
```
Here the request method is specified (TrailLamp currently supports only GET and POST requests)

The first parameter is the relative route path or url

The second parameter is the Controller name and the controller method separated by an @ sign
Routes can also be called with functions. Eg

```php
  $routes->get("/", function(){
       echo "ok";
  });
```

All request values(like form values whose action was set to a specified route) can be gotten via your controller $request std class as follows

```php
  $name = $this->request->name 
```
Remember to use the appropriate request method in your routes file

## Parameterized routes
Routes with parameters are called with the parameter names in  curly brackets example

```php
  $routes->get("/about/{name}", "Controller@Main");
```
Thus visiting url/about/Etorojah will call the Main method in the Controller class.

Multiple parameters are supported. To get the values of the parameters in the controller method, simply use

```php
  $this->params[string param-name]

```

eg 
```php
  $this->params["name"]
```
will return Etorojah and can be used to perform any necessary action.

Parameters cannot be passed to a function. Always use controllers

## Models
Models relate with the database directly.
Every model created inherits the parent class TrailModel.

### Creating Models
To create a model, run the following command on the TrailLamp console
```c#
  create model Modelname
 ```
Model names should start with a capital letter and the filename must be the same as the class name

By default, four model methods are created(create, read, update and delete). These methods can be deleted if not needed and your own defined methods called.

### Performing queries with a model
Call the query method from your model method eg getprod() with the query string as a parameter
```php
  $q = $this->query("SELECT * FROM products" );
 ```
 You can then manipulate your results as seen fit eg
 ```php
  while($r= mysqli_fetch_array($q)){
     //your code
      return result;
  }
 ```
Remember to always validate and sanitize your inputs before making queries

In your controller class method, reference the model using the include() method
```php
  include("path/Model.php");
```
create a new instance of the model
```php
  $prod = new ProductModel();
```
And call the model method to get the results
```php
  $prod->getprod();
```

### Deleting a model
Simply run the following command on your TrailLamp console
```
  delete model ModelName
```
### Controllers
Controller perform majority of the app functions.

To create a controller, run the following command on the TrailLamp console
```
  create controller ControllerName
 ```
 Controller file name and class name must be the same.
All controllers inherit the default TrailLamp controller class

Views can be called via the controller using
```php
  $this->view(string file name)
 ```
 Do not include the file extension

## Views
All files in the view folder are views.

To create a view simply run the following on the TrailLamp console
```
  create view ViewName
 ```
 Models can be called within views for example when displaying data from database 
 
 First reference the model file
 
 Create an instance of the class
 
And call the class method from the view

Just three lines suffice in views
```php
     include("Model.php");
     $prod = new Model();
     $prod->get-products();
```
In your model method the query and result can be echoed 
```php
  $q = $this->query("SELECT * FROM products" );
  while($r= mysqli_fetch_array($q)){
    echo '<h1>'.$r["name"].'</h1> ';
  }
```
##Console
The console helps your easily run TrailLamp commands
###Console commands
Create model
```
  create model Modelname
```
Create controller
```
create controller ControllerName
```
Create view
```
create view ViewName
```
View error log
```
view error log
```
Clear error log
```
clear error log
```
Delete model
```
delete model ModelName
```
Delete view
```
delete view ViewName
```
Delete controller
```
delete controller ControllerName
```
##Migrations
To perform migrations simply rename your migration file to migrations.sql and then place in the migrations directory

Then point to https://url-path/migrations/migrations.php on your browser example
```
https://traillamp.test/migrations/migrations.php
```

##Encryptions
To encrypt a text or url, first include the utility/Encryption.php file

Create a new instance of the class Encryption()

And call the appropriate method encrypt() or decrypt() with the string/hash as parameter

```php
include("utility/Encryption.php");
$enc = new Encryption();
$enc->encrypt("Hi"); or 
$enc->decrypt("hdhwkugehh48u38y38r88div3");
```

##Errors
Any error while developing is shown on the screen. The error is also logged into the error_log file for reference purposes
The error log file can be cleared with the clear error log command

##Contributions
Any issues found, please create an issue.

To contribute to this project, send an email to etorojahokon100@gmail.com or call +234 803 264 5840






  









