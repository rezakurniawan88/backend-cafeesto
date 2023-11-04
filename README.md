# Backend Cafeesto

[![PHP Version](https://img.shields.io/badge/PHP-8.1-purple.svg)](https://www.php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10-red.svg)](https://laravel.com)


## Technologies

- PHP
- Laravel
- MySQL

## Getting Started

1. Clone repositori :
```bash
git clone https://github.com/rezakurniawan88/backend-cafeesto.git
cd backend-cafeesto
```
2. Install dependensi :
```bash
composer install
```
3. Konfigurasi env :
```bash
cp .env.example .env
```
4. Kemudian isikan pengaturan koneksi database.
5. Generate APP KEY :
```bash
php artisan key:generate
```
6. Generate JWT Secret Key :
```bash
php artisan jwt:secret
```
7. Lakukan migrate database :
```bash
php artisan migrate
```
8. Jalankan development server : 
```bash
php artisan serve
```


### Note :
Link repo frontend cafeesto : https://github.com/rezakurniawan88/cafeesto