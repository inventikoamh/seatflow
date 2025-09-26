# SeatFlow

A modern Laravel 11 application with user management, built with shadcn/ui styling, React components, and mobile-responsive design.

## Features

- ✅ User Authentication (Login/Register)
- ✅ Responsive Design (Mobile-first)
- ✅ Theme Switching (Light/Dark mode)
- ✅ User Dashboard
- ✅ MySQL Database Integration
- ✅ Modern UI with shadcn/ui components
- ✅ React + TypeScript frontend
- ✅ Tailwind CSS styling
- ✅ Laravel 11 with latest features

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 5.7 or higher

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd SeatFlow
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Configuration**
   Update your `.env` file with the database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=seatflow
   DB_USERNAME=seatflow
   DB_PASSWORD=root
   ```

6. **Create Database**
   Create the MySQL database:
   ```sql
   CREATE DATABASE seatflow;
   ```

7. **Run Migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed Database**
   ```bash
   php artisan db:seed
   ```

9. **Build Assets**
   ```bash
   npm run build
   ```

10. **Start Development Server**
    ```bash
    php artisan serve
    ```

## Default Users

After seeding, you can login with:

- **Admin User**: `admin@seatflow.com` / `password`
- **Test User**: `test@seatflow.com` / `password`

## Development

### Running in Development Mode

1. **Start Laravel Server**
   ```bash
   php artisan serve
   ```

2. **Start Vite Dev Server** (in another terminal)
   ```bash
   npm run dev
   ```

### Building for Production

```bash
npm run build
```

## Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - User's email (unique)
- `password` - Hashed password
- `theme_preference` - User's theme preference (light/dark)
- `email_verified_at` - Email verification timestamp
- `remember_token` - Remember me token
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Theme System

The application supports both light and dark themes:
- Users can toggle themes using the theme button
- Theme preference is saved per user
- Themes are applied globally across the application
- Smooth transitions between theme changes

## Mobile Responsiveness

The application is fully responsive and optimized for:
- Mobile phones (320px+)
- Tablets (768px+)
- Desktop (1024px+)
- Large screens (1280px+)

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: React + TypeScript with Blade templates
- **UI Components**: shadcn/ui components with Radix UI
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Build Tool**: Vite 5
- **Styling**: Tailwind CSS with custom CSS variables
- **Language**: TypeScript for frontend components

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
