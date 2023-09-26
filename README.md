# Laravel_Vue_Bulletinboard

Getting Started

1. Clone the repository
   
   git clone https://github.com/Yin-Myat-Noe-Lwin/Laravel_Vue_Bulletinboard.git

2. Change directory to the "backend" folder:

   cd Laravel-Vue-Bulletinboard/backend

3. Install Composer dependencies.

   composer install

4. Copy the .env.example file to .env and configure database connection settings.

   cp .env.example .env

5. Generate the application key:

   php artisan key:generate

6. Run the database migrations and seed the database.

   php artisan migrate --seed

7. Start the Laravel development server.

   php artisan serve

8. Change directory to the "frontend" folder.

   cd Laravel-Vue-Bulletinboard/frontend

9. Install Node.js dependencies using npm or yarn.

   npm install
      or
   yarn install

10. Configure the API base URL.

    In the frontend/src/axios.js file, update the BASE_URL constant to match the URL of your Laravel backend API.

11. Start the Vue.js development server.

    npm run serve
       or
    yarn serve
