# Project Management System (Laravel 10)

## 📌 Project Overview
This is a demo **Project Management System**, designed to demonstrate clean architecture, service-based business logic, and best practices in Laravel development.

The system allows **investors** and **borrowers** to manage projects, track their lifecycle, upload images, and control project status transitions in a structured and secure way.

---

## 🚀 Core Features

- Create and manage projects.
- Edit project details with validation and authorization.
- Delete projects along with all associated images.
- Manage project status through predefined lifecycle stages:
  - Review
  - Pre-Approval
  - Funding
  - Active
  - Completed
- Enforce valid status transitions using domain rules.
- Upload a main project image and multiple gallery images.
- Automatically remove project images when a project is deleted.
- Display projects with:
  - Search
  - Pagination
  - AJAX-based filtering
- Automatically calculate and display project funding progress percentage.

---

## 🧠 Technical Highlights

- Clean separation of concerns:
  - Controllers handle HTTP requests.
  - Services handle business logic.
  - Models enforce domain rules.
- Transaction-safe project status updates.
- Centralized validation using Form Request classes.
- Laravel Eloquent relationships for image management.
- Uses Laravel Storage API for file handling.

---

## 🛠️ Requirements

- PHP >= 8.1
- Laravel 12
- MySQL / MariaDB
- Composer
