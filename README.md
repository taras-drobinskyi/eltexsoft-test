
## About EltexSoft test task

This project made s test task for EltexSoft

## Admin Panel

- Material design dashboard
- Role based access control
- User Management by roles and permissions

##API (REST/JSON)

Api Token you can get from user details at admin panel

Get all users
    
    curl --location --request GET '{domain}/api/users' \
    --header 'api_token: {api_token}}' \

Get single user by id

    curl --location --request GET '{your_domain}/api/users/{user_id}}' \
    --header 'api_token: {api_token}' \
        
Create user (Required fields in example below)

       curl --location --request POST '{your_domain}/api/users' \
       --header 'api_token: {api_token}}' \
       --header 'Content-Type: application/json' \
       --data-raw '{
        "first_name": "",
        "last_name": "",
        "status": "",
        "role": "",
        "notes": "",
        "email": "",
        "password": "",
        "date_of_birth": ""
       }'
       
Update User 

    curl --location --request POST '{your_domain}/api/users' \
    --header 'api_token: {token}}' \
    --header 'Content-Type: application/json' \
    --data-raw '{
    	"first_name": "",
    	"last_name": "",
    	"status": "",
    	"role": "",
    	"notes": "",
    	"email": "",
    	"password": "",
    	"date_of_birth": ""
    }'
    
Delete user

    curl --location --request DELETE '{your_domain}}/api/users/16' \
    --header 'api_token: {token}' \


## Installation

    git clone https://github.com/taras-drobinskyi/eltexsoft-test.git
    cd eltexsoft-test
    composer install
    sudo cp .env.example .env
    sudo chown $(whoami) .env
    
Change connection details to db in .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
After db connection setup:

    php artisan key:generate
    php artisan config:cache
    php artisan migrate:fresh --seed --seeder=PermissionsSeeder
    php artisan serve

This makes all the migration and creates first user with

    login: admin@eltexsoft.com
    password: password

