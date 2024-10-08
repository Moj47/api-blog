# API Blog

Welcome to the **API Blog** repository! This project implements a RESTful API for a blogging platform developed using **Laravel** and **MySQL**. The platform features user authentication, role-based access control, and comprehensive testing capabilities.

## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Usage](#usage)
- [API Endpoints](#api-endpoints)
  - [User Endpoints](#user-endpoints)
  - [Post Endpoints](#post-endpoints)
  - [Comment Endpoints](#comment-endpoints)
- [Testing](#testing)
- [Database Structure](#database-structure)
- [Contributing](#contributing)
- [License](#license)

## Features

- **User Authentication:** Users must log in to interact with posts or comments.
- **Role-Based Access Control:**
  - Only admins can create, update, or delete posts and categories.
  - Regular users can create comments but cannot create, update, or delete posts or categories.
- **Post Management:** Create, read, update, and delete blog posts.
- **Comment Management:** Users can add comments to posts.
- **Factories for Models:** All models come with factories to easily generate test data.
- **Comprehensive Testing:** Full test coverage for all models and functionalities.

## Technologies Used

- **Framework:** [Laravel](https://laravel.com/)
- **Database:** [MySQL](https://www.mysql.com/)
- **Testing:** [PHPUnit](https://phpunit.de/) for unit and feature testing
- **Authentication:** Laravel's built-in **Sanctum** or **JWT** for secure user authentication

## Getting Started

### Prerequisites

- Install [PHP](https://www.php.net/) (version 7.3 or higher)
- Install [Composer](https://getcomposer.org/)
- Install MySQL server

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/api-blog.git
   cd api-blog
