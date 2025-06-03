## Step by step install project

-   **Step 1: Check requirement**
    -   Apache version 2.4.62
    -   php version 8.2.26
    -   postgresql 16.6
    -   redis version 5.0.14.1
-   **Step 2: Clone project**
-   **Step 3: Open cmd or terminal run command below to install package**
    ```
    composer i
    ```
-   **Step 4: Duplicate file `.env.example` and rename to `.env`**
-   **Step 5: Open cmd or terminal run command below to generate key**
    ```
    php artisan key:generate
    ```
-   **Step 6: Run start web server apache, php, postgresql, redis**
-   **Step 7: Create new schema in postgresql**
-   **Step 8: Open file `.env` , get information connect from `Step 7` to edit**
    ```
    DB_CONNECTION=pgsql
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ```
-   **Step 9: Open cmd or terminal run command below to migrate database**
    -   **Step 9.1: Migration all tables**
        ```
        php artisan migrate:all
        ```
    -   **Step 9.2: Migration rollback all**
        ```
        php artisan migrate:rollback-all
        ```
-   **Step 10: Execute all sql query in file `data_init.sql`**
-   **Step 11: Open file `.env` , get information connect redis to edit**
    ```
    REDIS_CLIENT=predis
    REDIS_HOST=
    REDIS_PASSWORD=
    REDIS_PORT=
    ```
-   **Step 12: Open cmd or terminal run command below to generate access and refresh secret key**
    -   **Step 12.1: Copy result, create assign value for new variable `ACCESS_TOKEN_SECRET=`**
    ```
    php -r 'echo base64_encode(random_bytes(32));'
    ```
    -   **Step 12.2: Copy result, create assign value for new variable `REFRESH_TOKEN_SECRET=`**
    ```
    php -r 'echo base64_encode(random_bytes(32));'
    ```
-   **Step 13: Open cmd or terminal run command below to start web**
    ```
    php artisan serve
    ```
