# News Hub Backend

This is a Laravel backend for the News Hub project. It is fully containerized using Docker Compose for easy local development and deployment.

## Prerequisites

- [Docker](https://www.docker.com/get-started) and [Docker Compose](https://docs.docker.com/compose/) installed
- [Git](https://git-scm.com/) for cloning the repository

## Quick Start

1. **Clone the repository:**
   ```sh
   git clone https://github.com/ore-codes/news-hub-be.git
   cd news-hub-be
   ```

2. **Copy the example environment file and edit as needed:**
   ```sh
   cp .env.example .env
   # Edit .env to set your database, Meilisearch, and API keys
   ```

3. **Build and start all services:**
   ```sh
   docker-compose up --build -d
   ```
   This will build the PHP, Nginx, MySQL, and Meilisearch containers and start them in the background.

4. **(First time only) Generate the application key:**
   ```sh
   docker-compose exec app php artisan key:generate
   ```

5. **Access the application:**
   - API: [http://localhost:8000](http://localhost:8000)
   - Meilisearch: [http://localhost:7700](http://localhost:7700)

6. **API Documentation:**
   - Swagger docs are generated at `/api/documentation`

## Notes
- The Laravel scheduler runs in a dedicated container (`scheduler`) and will execute scheduled tasks as defined in `routes/console.php`.
- Database data is persisted in a Docker volume (`dbdata`).
- Meilisearch data is persisted in a Docker volume (`meilisearch_data`).
- If you change your `.env` file, restart the containers:
  ```sh
  docker-compose restart
  ```

## API Keys Setup

This project requires API keys for four news platforms. You must obtain these keys and add them to your `.env` file:

- `NEWSAPI_KEY`
- `GUARDIAN_KEY`
- `NYTIMES_KEY`
- `EVENTREGISTRY_KEY`

### How to Obtain API Keys

**1. NewsAPI**
- Go to [https://newsapi.org/register](https://newsapi.org/register)
- Sign up for a free account
- After verifying your email, you will find your API key in the dashboard
- Add it to your `.env` as `NEWSAPI_KEY=your_key_here`

**2. The Guardian**
- Go to [https://open-platform.theguardian.com/access/](https://open-platform.theguardian.com/access/)
- Register for an API key
- After approval, you will receive your key by email or in your account dashboard
- Add it to your `.env` as `GUARDIAN_KEY=your_key_here`

**3. NYTimes**
- Go to [https://developer.nytimes.com/accounts/create](https://developer.nytimes.com/accounts/create)
- Create an account and log in
- Go to "Apps" and create a new app to get your API key
- Add it to your `.env` as `NYTIMES_KEY=your_key_here`

**4. EventRegistry(NewsAPI.AI)**
- Go to [https://www.newsapi.ai/register](https://www.newsapi.ai/register)
- Register for a free account
- After logging in, go to your profile to find your API key
- Add it to your `.env` as `EVENTREGISTRY_KEY=your_key_here`
