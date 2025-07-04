# News Hub

## Running with Docker

This project is fully dockerized for easy setup and portability. You only need Docker and Docker Compose installed on your machine.

### 1. Clone the repository
```sh
git clone https://github.com/ore-codes/news-hub-be.git
cd news-hub-be
```

### 2. Prepare your environment
- Copy your environment file:
  ```sh
  cp .env.example .env
  ```
- Edit `.env` and set the following for SQLite:
  ```env
  DB_CONNECTION=sqlite
  DB_DATABASE=/var/www/database/database.sqlite
  
  NEWSAPI_KEY=<api key from newsapi.ord>
  GUARDIAN_KEY=<api key from guardian>
  NYTIMES_KEY=<api key from new york times>
  EVENTREGISTRY_KEY=<api key from event registry (newsapi.ai)>

  JWT_SECRET=<generate with php artisan jwt:secret>
    
  SCOUT_DRIVER=meilisearch
  MEILISEARCH_HOST=http://meilisearch:7700
  MEILISEARCH_KEY=<generate master key>
  ```
- Ensure the file `database/database.sqlite` exists. If not, create it:
  ```sh
  touch database/database.sqlite
  ```

### 3. Build and start the containers
```sh
docker-compose up --build
```
- The Laravel app will be available at [http://localhost:8000](http://localhost:8000)
- Meilisearch will be available at [http://localhost:7700](http://localhost:7700)

### 4. Install dependencies and migrate the database
Open a new terminal and run:
```sh
docker-compose exec app composer install
```
```sh
docker-compose exec app php artisan migrate
```

### 5. Stopping the application
```sh
docker-compose down
```

---

## Troubleshooting
- If you change dependencies, rebuild with `docker-compose build`.
- If you encounter permission issues, ensure the `storage/` and `bootstrap/cache/` directories are writable by the container.
- For Laravel logs, check `storage/logs/`.

---

## Additional Notes
- The `database/database.sqlite` file is git-ignored. Each developer should create their own local copy.
- For production, consider using a web server like Nginx and configuring SSL.