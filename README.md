# 🌍 TourJoy - Travel Management Platform (Symfony Edition)

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php)
![Symfony](https://img.shields.io/badge/Symfony-6+-black?style=for-the-badge&logo=symfony)
![Composer](https://img.shields.io/badge/Composer-2+-orange?style=for-the-badge&logo=composer)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

*A modern Symfony application for seamless travel and tourism management*

[Features](#-features) • [Installation](#-installation) • [Usage](#-usage) • [Contributing](#-contributing)

</div>

---

## Features

### **Accommodation Management**
- Browse and manage accommodations with detailed information
- Image galleries for visual representation
- Advanced filtering and search capabilities

### **Monument Explorer**
- Discover historical monuments and landmarks
- Detailed descriptions with rich media content
- Interactive map integration

### **Professional Guide Services**
- Browse certified tour guides
- View ratings and reviews
- Secure booking system with payment integration

### **Feedback System**
- Leave detailed reviews for guides and accommodations
- Rating system for quality assurance
- Community-driven recommendations

### **User Management**
- Secure authentication with email verification
- User profiles with customization options
- Role-based access control (Admin/Client)

### **Analytics Dashboard**
- Real-time statistics and reports
- Booking analytics with visual charts
- Revenue tracking and insights

### **Modern UI/UX**
- Clean, responsive design
- Multiple theme support
- Intuitive navigation

---

## Architecture

```
TourJoy/
├── assets/                  # Frontend assets (CSS, JS, images)
├── bin/                     # Symfony CLI and console
├── config/                  # Configuration files
├── migrations/              # Doctrine migrations
├── public/                  # Public web root (entry point)
├── src/                     # PHP source code (Controllers, Entities, Services)
├── templates/               # Twig templates
├── tests/                   # Automated tests
├── translations/            # Translation files (i18n)
├── .env                     # Environment variables
├── composer.json            # Composer dependencies
├── symfony.lock             # Symfony dependencies lock
└── docker-compose.yaml      # Docker configuration (if present)
```

---

## Quick Start

### Prerequisites
- **PHP 8.1+**
- **Composer 2+**
- **Symfony CLI** (optional, for local development)
- **Node.js & Yarn** (for managing frontend assets, if used)
- **MySQL 8.0+** (or compatible database)
- **Docker & Docker Compose** (optional, for containerized setup)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yasmine-mmz/TourJoy.git
   cd TourJoy
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Configure environment variables**
   - Copy `.env` to `.env.local` and update database and mailer credentials as needed.

4. **Run database migrations**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Install frontend assets** (if using Webpack Encore)
   ```bash
   yarn install
   yarn dev
   ```

6. **Start the Symfony local server**
   ```bash
   symfony server:start
   ```
   Or use the built-in PHP server:
   ```bash
   php -S localhost:8000 -t public
   ```

7. **Access the application**
   - Open [http://localhost:8000](http://localhost:8000) in your browser.

---

## Tech Stack

| Technology      | Purpose                  | Version      |
|-----------------|-------------------------|--------------|
| **PHP**         | Core Language           | 8.1+         |
| **Symfony**     | Web Framework           | 6.x+         |
| **Composer**    | Dependency Management   | 2.x+         |
| **Twig**        | Templating Engine       | Latest       |
| **Doctrine ORM**| Database ORM            | Latest       |
| **MySQL**       | Database                | 8.0+         |
| **Webpack Encore** | Asset Management     | Latest       |
| **PHPUnit**     | Testing                 | Latest       |
| **API Platform**| REST APIs (optional)    | Latest       |

---

## Key Components

### Controllers & Services
- `src/Controller/AccommodationController.php` - Accommodation management
- `src/Controller/GuideController.php` - Guide booking interface
- `src/Controller/PaymentController.php` - Payment processing
- `src/Controller/SubscriptionController.php` - Subscription management
- `src/Service/` - Business logic and utilities

### Entities & Repositories
- `src/Entity/` - Doctrine entities (User, Accommodation, Booking, etc.)
- `src/Repository/` - Custom query logic

### UI & Templates
- `templates/` - Twig templates for UI
- Modern CSS styling with multiple themes (`assets/styles/`)
- Responsive layouts and dynamic components

---

## Customization

### Themes
The application supports multiple CSS themes located in:
- `assets/styles/main.css` - Main theme
- `assets/styles/backoffice.css` - Admin interface
- `assets/styles/alternative.css` - Alternative theme

#### Adding Custom Themes
1. Create a new CSS file in `assets/styles/`
2. Define your custom styles
3. Import or include the theme in your base Twig layout.

---

## Configuration

### Database Setup
1. Install MySQL 8.0+
2. Create a database: `tourjoy_db`
3. Update the `DATABASE_URL` in your `.env.local` file

### Email Configuration
Set up the `MAILER_DSN` variable in your `.env.local` for sending notifications.

---

## Testing

Run automated tests with:

```bash
php bin/phpunit
```

---

## Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Commit your changes**
   ```bash
   git commit -m 'Add amazing feature'
   ```
4. **Push to the branch**
   ```bash
   git push origin feature/amazing-feature
   ```
5. **Open a Pull Request**

### Code Style
- Follow Symfony and PSR coding standards
- Add PHPDoc comments for public methods
- Ensure CSS follows BEM methodology
- Test your changes thoroughly

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Contact & Support

- **Issues**: [GitHub Issues](https://github.com/yasmine-mmz/TourJoy/issues)
- **Email**: support@tourjoy.com

---

## Acknowledgments

- Symfony and PHP open source community
- Contributors and testers
- Inspiration from JavaFX original project

---

<div align="center">

**TourJoy** - *Making travel planning joyful!*

[![GitHub stars](https://img.shields.io/github/stars/yasmine-mmz/TourJoy?style=social)](https://github.com/yasmine-mmz/TourJoy/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/yasmine-mmz/TourJoy?style=social)](https://github.com/yasmine-mmz/TourJoy/network)

</div>
