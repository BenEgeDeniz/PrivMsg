# PrivMsg

A private, end-to-end encrypted messaging application built with Laravel and Vue.js, featuring WebAuthn for secure authentication.

## About The Project

(Add a more detailed description of your project here. What problem does it solve? What are the key features?)

Built with:
*   Laravel `^12.0`
*   Vue.js `^3.5`
*   Vue Router `^4.5`
*   Vuex `^4.1`
*   Tailwind CSS `^4.0`
*   Vite
*   WebAuthn (`laragear/webauthn`)

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
5.  Copy `.env.example` to `.env` and configure your environment variables (especially `DB_*` for database connection and `VAPID_*` keys if using Web Push)
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

## Usage

(Add instructions or examples on how to use the application, register an account, add passkeys via WebAuthn, and send/receive messages.)

## Contributing

Contributions are welcome! Please follow standard fork & pull request workflows.

## License

(Specify your project's license, e.g., Distributed under the MIT License. See `LICENSE` file for more information.)

## Contact

Ege Deniz Kaçar - ege@benegedeniz.com

Project Link: [https://github.com/BenEgeDeniz/PrivMsg](https://github.com/BenEgeDeniz/PrivMsg)
