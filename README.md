<p align="center"><a href="https://praxxys.global/" target="_blank"><img src="https://swiperight.praxxys.ph/themes/main/images/logo.png" width="400" alt="Praxxys Logo"></a></p>

## System Requirements

- PHP 8.2 or latest
- Composer 2.8.8
- Laravel 12
- MySQL 8
- Apache/NGINX latest version


## Installation

Clone the backend praxxys-product-api repository.
```bash
$ git clone git@github.com:rogertrocio/praxxys-product-api.git
$ cd praxxys-product-api
$ composer install
$ cp .env.example .env
$ php artisan key:generate
```

Create a MySQL database named praxxys_product.
```bash
$ mysql -u user -p

create DATABASE praxxys_product;
```

Update .env file for database configuration.
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=praxxys_product
DB_USERNAME=user
DB_PASSWORD=password
```

Migrate the database and populate the data.
```bash
$ php artisan migrate
$ php artisan db:seed
```

Create the symbolic link of storage folder to public folder.
```bash
$ php artisan storage:link
```

Optimize the application.
```bash
$ php artisan optimize
```

Serve the application in localhost.
```bash
$ php artisan serve
```

## Testing API Endpoints

Instructions:

1. Download Postman API platform. [Download here.](https://www.postman.com/downloads/) 
2. In the Postman application, click the `Import` button and select the [Praxxxys Product postman collection](docs/Praxxys%20Product.postman_collection.json) located in the docs folder.
3. To test an api endpoint, Select `Login` request under Auth folder.
4. Click `Send` button to send a request to the application.
5. If the login request is successful, the response should be:
```json
{
    "data": {
        "token": "1|A9hyGNDnMjnANhaKSbEQNlwHGKa3XrwYsw6SfFfUe1b9b835",
        "token_type": "bearer",
        "user": {
            "id": 1,
            "name": "John Doe",
            "first_name": "John",
            "last_name": "Doe",
            "username": "john.doe",
            "email": "john.doe@praxxys.com",
            "created_at": "April 20, 2025 10:43 AM",
            "updated_at": "April 20, 2025 10:43 AM"
        }
    }
}
```
