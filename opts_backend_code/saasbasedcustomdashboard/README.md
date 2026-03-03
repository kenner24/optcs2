## How to use

# Local
- Clone the repository with `git clone <Repo Link>`
- Copy `.env.example` file to `.env` and edit database credentials there
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed`
- To add the company and staff data as well run this command `php artisan db:seed --class=CompanyAndStaffSeeder`
- Run `php artisan serve`
- To run the background jobs run this command on terminal `php artisan queue:work`
- To run the task scheduler run this command `php artisan schedule:work` or set a cron job locally to run the task scheduler.
- To set cron job locally 
    - Install cron on system(Linux) `sudo apt-get install cron`
    - Then run this command on terminal `sudo crontab -e`
    - Add this line to the file `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
- Go to the `http://127.0.0.1:8000/api/documentation` url to check api documentation.

# Production
- Clone the repository with `git clone <Repo Link>`
- Copy `.env.example` file to `.env` and update with the prod credentials
- Run `composer install --no-dev --optimize-autoloader` 
- Run `composer dump-autoload`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed`
- Run `php artisan serve`
- Set the cron job on the server using this command `sudo crontab -e` (Install cron on server if not present using command `sudo apt-get install cron`)
- Add this line in the cron file  `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
- Start the queue worker to run the background jobs, run this command on terminal `php artisan queue:work`.
- Go to the `<Prod_URL>/api/documentation` url to check api documentation.

## Credentials
superAdmin: superAdmin@example.com/admin@12345


## Background jobs
1. To run jobs using the database add this in the env file `QUEUE_CONNECTION=database`
2. Command to run the jobs is `php artisan queue:work`