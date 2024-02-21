## Patient Meal Planning Laravel Application

This Laravel application is designed for retrieve records by month and year using api with params start_date (yyyy-mm-dd) and end_date (yyyy-mm-dd) at regular intervals (5mins) (using laravel scheduler). Follow the instructions below to set up the project on your local machine.

## Getting Started
## Clone the Repository
``git clone https://github.com/pringal/patient_meal_planning.git``

## Set Up Environment Variables

Create a copy of the .env.example file and save it as .env. Update the following section in the .env file with your database information:


``DB_CONNECTION=mysql``

``DB_HOST=your_database_host``

``DB_PORT=your_database_port``

``DB_DATABASE=your_database_name``

``DB_USERNAME=your_database_username``

``DB_PASSWORD=your_database_password``



## Install Dependencies
Run the following command to install the project dependencies:

``composer install``

## Run Migrations and Seed Database
Run the migrations to set up the database schema:

``php artisan migrate``

Seed the database with initial data:

``php artisan db:seed --class=PatientMealPlanningSeeder``

## Set Up Scheduler
Add the following line to your server's crontab to run Laravel's scheduler at regular intervals:

``* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1``

## Direct Command for the api execution in terminal
``php artisan app:call-meal-planning-api``

## Direct run the api (GET)
``http://127.0.0.1:8000/api/meal-planning?start_date=2022-01-01&end_date=2023-02-31``


