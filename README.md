# Bazaar

Bazaar is a web application built using the Laravel PHP framework. It provides a modern starting point for building scalable and robust web applications with support for user authentication, database migrations, and a Blade-based front end.

## Features

- User authentication and management
- RESTful API endpoints
- Database migrations and seeders
- Responsive Blade-based front end
- Laravel Breeze integration
- Asset bundling with Vite

## Installation

### Prerequisites

Ensure you have the following installed:

- PHP >= 8.0
- Composer
- Node.js and npm
- MySQL or another supported database

### Setup Steps

1. Clone the repository:

   ```bash
   git clone https://github.com/N1TROGUE/Bazaar.git
   cd Bazaar
   ```
2. Install PHP dependencies:

   ```bash
   composer install
   ```
3. Install JavaScript dependencies:

   ```bash
   npm install
   ```
4. Copy and configure the environment file:

   ```bash
   cp .env.example .env
   ```
   Update the .env file with your database and other configuration details.
5. Generate the application key:
   ```bash
   php artisan key:generate
   ```
6. Run database migrations:
   ```bash
   php artisan migrate
   ```
7. Compile front-end assets:
   ```bash
   npm run dev
   ```
8. Start the development server:
   ```bash
   php artisan serve
   ```
   




















