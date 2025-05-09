# Nette Todo List

A simple, elegant todo list application built with the Nette Framework and Tailwind CSS.

## Features

- Create, toggle, and delete todo items
- Responsive design with Tailwind CSS
- AJAX support for a smooth user experience
- SQLite database for data storage
- Clean MVC architecture

## Screenshots

(Add screenshots here)

## Requirements

- PHP 8.4 or higher
- Composer
- Node.js and npm

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/rajtik76/nette-todo-list.git
   cd nette-todo-list
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install
   ```

4. Build the CSS:
   ```bash
   npm run build
   ```

5. Make sure the `temp/` and `log/` directories are writable:
   ```bash
   chmod -R 777 temp/ log/
   ```

## Usage

1. Start the PHP development server:
   ```bash
   php -S localhost:8000 -t www
   ```

2. Open your browser and navigate to `http://localhost:8000`

3. For development with automatic CSS rebuilding:
   ```bash
   npm run watch
   ```

## Project Structure

- `app/` - Application source code
  - `Core/` - Core application components
  - `Model/` - Data models and business logic
    - `Todo/` - Todo-related models and services
  - `Presentation/` - Presenters and templates
    - `Todo/` - Todo-related presenters and templates
- `config/` - Configuration files
- `log/` - Log files
- `resources/` - Frontend resources
  - `css/` - CSS source files
- `temp/` - Temporary files and cache
- `tests/` - Test files
- `vendor/` - Composer dependencies
- `www/` - Public directory (document root)
  - `css/` - Compiled CSS files

## Technologies Used

- [Nette Framework](https://nette.org/) - PHP framework for web development
- [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS framework
- [Naja.js](https://naja.js.org/) - AJAX library for Nette Framework
- SQLite - Database engine

## Development

### Static Analysis

Run PHPStan for static code analysis:
```bash
composer phpstan
```

### CSS Compilation

Build the CSS once:
```bash
npm run build
```

Watch for CSS changes during development:
```bash
npm run watch
```

## License

This project is licensed under multiple licenses - see the `composer.json` file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.