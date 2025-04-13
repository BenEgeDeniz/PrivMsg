# PrivMsg

A private, end-to-end encrypted messaging application built with Laravel and Vue.js, featuring WebAuthn for secure authentication.

Built with:
*   Laravel `^12.0`
*   Vue.js `^3.5`
*   Vue Router `^4.5`
*   Vuex `^4.1`
*   Tailwind CSS `^4.0`
*   Vite

## Getting Started

### Prerequisites

*   PHP `^8.2`
*   Composer
*   Node.js & npm
*   A database server supported by Laravel (e.g., MySQL, PostgreSQL, SQLite)

### Installation

1.  Clone the repo
    ```sh
    git clone https://github.com/BenEgeDeniz/PrivMsg.git
    ```
2.  Navigate to the project directory
    ```sh
    cd PrivMsg
    ```
3.  Install PHP dependencies
    ```sh
    composer install
    ```
4.  Install JS dependencies
    ```sh
    npm install
    ```
5.  Copy `.env.example` to `.env` and configure your environment variables (especially `DB_*` for database connection)
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```
6.  Configure your database connection details in the `.env` file.

7.  Run database migrations
    ```sh
    php artisan migrate
    ```
8.  Compile assets for development (or `npm run build` for production)
    ```sh
    npm run dev
    ```
9.  Serve the application
    ```sh
    php artisan serve
    ```

    The application should now be running, typically at `http://127.0.0.1:8000`.

## License

PrivMsg by BenEgeDeniz is marked with CC0 1.0 

## Contact

Ege Deniz Kaçar - ege@benegedeniz.com

Project Link: [https://github.com/BenEgeDeniz/PrivMsg](https://github.com/BenEgeDeniz/PrivMsg)
