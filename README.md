<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

# Headless E-Commerce API (Laravel)

This repository contains the robust backend API developed with **Laravel **, designed to power a modern headless e-commerce platform (with a Next.js frontend and Prestashop Back office). It manages the entire backend operations including authentication, product catalog, cart manipulation, and a comprehensive checkout process.

## 🚀 Features

- **Authentication & Security:** 
  - Token-based authentication using **Laravel Sanctum**.
  - Support for both authenticated users and guest sessions.
- **Product & Catalog Management:**
  - Complete endpoints for Products, Categories (root, main, hierarchy), and Filters.
  - Image handling for product galleries.
- **Cart & Order System:**
  - Advanced Cart management (add, update, remove items, clear cart).
  - Discount/Voucher integration (Cart Rules) for both users and guests.
- **Checkout Flow:**
  - Multi-step checkout process (Addresses, Carriers, Confirmation).
  - Seamless Guest Checkout support.
- **Data Structuring:**
  - Extensive use of **Laravel API Resources** for clean, consistent JSON responses.

## 🛠 Tech Stack

- **Framework:** [Laravel](https://laravel.com/)
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **Frontend Integration:** Built to serve a Next.js storefront
- **Architecture:** RESTful API Design

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/tadjedine/Laravel-API.git
   cd Laravel-API
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Environment Setup:**
   Copy the example `.env` file and configure your database settings:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the development server:**
   ```bash
   php artisan serve
   ```

## 📚 API Documentation

To view the API endpoints, you can run:
```bash
php artisan route:list --path=api
```


