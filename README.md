# Link Shortener
This is a simple link shortener application written in PHP. It allows users to enter a long URL and generates a shortened version of the link. The application uses a MySQL database for storing the original and shortened links.

## Prerequisites
Before running this application, make sure you have the following installed:

- PHP (version 5.6 or higher)
- Composer (https://getcomposer.org/)
- MySQL / MariaDB
- reCAPTCHA - Google

## Installation

1. Clone the repository:

   ```shell
   git clone https://github.com/shiwildy/Link-Shortener.git
   ```

2. Navigate to the project directory:
    ```shell
    cd Link-Shortener
    ```

3. Install the dependencies using Composer:
    ```shell
    composer install
    ```

4. Set up the environment variables:

Rename the .env.example file to .env.
Open the .env file and set the appropriate values for the database connection (DB_HOST, DB_NAME, DB_USER, DB_PASS).
and setup reCaptcha sitekey & secretkey

5. Import the database schema:

Create a new MySQL database.
Import the linkshortener.sql file located in the database directory into the newly created database.

6. Start the application:

You can use a local development server to run the application, such as PHP's built-in server:
```shell
php -S localhost:8000
```

or you can using hosting like cpanel, directadmin, vps, replit

## Usage
- Open your web browser and navigate to the application URL (e.g., http://localhost:8000).
- Enter a long URL into the input field and click the "Shorten" button.
- The application will generate a shortened link for the entered URL.
- Click on the shortened link to test it.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvement, please open an issue or submit a pull request.

## License
This project is licensed under the MIT License. See the LICENSE file for details.

