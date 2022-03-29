# Laravel Demo

## Description

A small demo of scraping data from a web page, or adding via uploading a CSV, and multi-facet filtering on the dataset. All done with [Laravel](https://laravel.com/) and [Livewire](https://livewire-framework.com/).

### Download data
##### To download CSV, go to http://mip-prd-web.azurewebsites.net/DataItemExplorer and follow these steps from 1 to 4.
![Downloading data | Laravel Demo](public/img/help-data-download.png)

### Admin portal

![Admin Portal | Laravel Demo](public/img/admin-1.png)

### All records

![All records | Laravel Demo](public/img/dashboard-1.png)

### Filter by an area name or calorific value or date

![Filter multiple columns | Laravel Demo](public/img/dashboard-2.png)

### Edit or Delete

![Editing | Laravel Demo](public/img/dashboard-3.png)

## Installation

1. Clone, `composer install`, `yarn install`, configure `.env` and your DB connection.

2. Migrate and seed the DB.

```bash
php artisan migrate
```

3. Go to "/admin" URL to load data using Fetch or by uploading a CSV.

4. Go to "/dashboard" to view the front-end.
