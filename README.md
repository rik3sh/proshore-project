## Installation Guide

1.	Clone the repo to your machine.
2.	Inside the project root, run `composer install`, `npm install` and `npm run build`.
3.	Copy `.example.env` to `.env` file: `cp .example.env .env`
4.	Create a new database and a user with all privileges & populate the necessary database environments. Also fill `APP_URL` and set `QUEUE_CONNECTION` to “database”.
5.	Create a SMTP provider and fill the necessary MAIL environments.
6.	Run `php artisan key:generate`.
7.	Run `php artisan migrate --seed`.
8.	Either run `php artisan serve` or use any other methods you want to run the project.
9.	You will need to run `php artisan queue:work` in a separate terminal and keep it running for mail queuesnpm inst