# News Hub Backend Test Suite

This directory contains comprehensive tests for the News Hub backend application.

## Test Structure

### Feature Tests (`tests/Feature/`)
- **AuthControllerTest.php** - Tests user registration, login, logout, and profile endpoints
- **ArticleControllerTest.php** - Tests article listing, filtering, search, and user preferences
- **FetchNewsArticlesCommandTest.php** - Tests the news fetching command and deduplication logic

### Unit Tests (`tests/Unit/`)
- **NewsApiServiceTest.php** - Tests API service integration and data transformation
- **ArticleModelTest.php** - Tests the Article model functionality and searchable features

## Running Tests

### Run All Tests
```bash
# Using Docker
docker-compose exec app php artisan test

# Or directly with PHPUnit
./vendor/bin/phpunit
```

### Run Specific Test Suites
```bash
# Run only Feature tests
./vendor/bin/phpunit --testsuite=Feature

# Run only Unit tests
./vendor/bin/phpunit --testsuite=Unit
```

### Run Specific Test Files
```bash
# Run auth tests only
./vendor/bin/phpunit tests/Feature/AuthControllerTest.php

# Run article model tests only
./vendor/bin/phpunit tests/Unit/ArticleModelTest.php
```

### Run Tests with Coverage
```bash
./vendor/bin/phpunit --coverage-html coverage/
```

## Test Coverage

### Authentication (AuthControllerTest)
- ✅ User registration with valid/invalid data
- ✅ User login with valid/invalid credentials
- ✅ User logout functionality
- ✅ Protected route access
- ✅ User profile retrieval

### Articles (ArticleControllerTest)
- ✅ Article listing and pagination
- ✅ Article search functionality
- ✅ Filtering by category, source, date
- ✅ Categories, sources, and authors endpoints
- ✅ User preferences management
- ✅ Preference-based article filtering

### API Services (NewsApiServiceTest)
- ✅ API data transformation
- ✅ Handling missing fields
- ✅ Error handling
- ✅ URL construction
- ✅ Response parsing

### Models (ArticleModelTest)
- ✅ Article creation and updates
- ✅ Searchable array generation
- ✅ Fillable fields validation
- ✅ Model deletion

### Commands (FetchNewsArticlesCommandTest)
- ✅ Command execution
- ✅ Article creation
- ✅ Duplicate prevention
- ✅ Content truncation
- ✅ Empty content handling
