## Micro Job Board api setup

- Clone the repository with the command `git clone`
- `cd` into the project directory
- Run `composer install` to install the project dependencies
- Create a database for the application
- Copy the `.env.example` file and rename it to `.env`
- Update the `.env` file with your database credentials
- Run `php artisan migrate` to migrate the database
- Run `php artisan storage:link` to link local file storage
- Run `php artisan DB:seed` to seed the database with requested test credentials
- Run `php artisan serve` to start the application
