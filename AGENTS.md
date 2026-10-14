# Repository Guidelines

## Project Structure & Module Organization

This repository contains a WordPress installation. Root PHP files bootstrap the application; `wp-admin/` provides administration screens, and `wp-includes/` contains core libraries. Keep site customizations in `wp-content/`, preferably in a custom plugin or child theme, to preserve them during updates.

- `wp-content/plugins/`: plugins, including Akismet.
- `wp-content/themes/`: Twenty Twenty-Three, Twenty Twenty-Four, and Twenty Twenty-Five.
- Theme `templates/` and `parts/`: block HTML templates; `patterns/`: PHP block patterns; `assets/`: CSS, fonts, and images; `theme.json`: editor settings and styles.
- `wp-content/uploads/`: runtime media files.

No project-level test directory or build configuration is present.

## Build, Test, and Development Commands

Run WordPress through a PHP web server with a configured MySQL/MariaDB database. Use `wp-config-sample.php` as the configuration reference.

- `php -l path/to/file.php`: check syntax for each changed PHP file.
- `cd wp-content/themes/twentytwentyfive && npm install`: install theme build dependencies; requires Node.js >=20.10.0 and npm >=10.2.3.
- From that theme directory, `npm run build`: minify `style.css` into `style.min.css` using PostCSS and cssnano.
- `npm run watch`: rebuild the minified stylesheet when the source changes.

These npm scripts apply specifically to Twenty Twenty-Five.

## Coding Style & Naming Conventions

Follow surrounding WordPress conventions: tabs for PHP indentation, snake_case functions, and a unique plugin/theme prefix for custom symbols. Preserve existing JSON, CSS, and block-template formatting. Use descriptive, lowercase, hyphenated filenames for new templates and patterns. Translate user-facing PHP strings with the appropriate text domain. Edit source CSS and regenerate minified output. No repository-wide formatter or linter configuration is provided.

## Testing Guidelines

No project test runner, test naming convention, or coverage threshold is configured. Validate changed PHP with `php -l`, then check affected frontend pages and administration/editor flows on a development installation. For visual changes, check desktop and mobile layouts and record screenshots. Document reproduction steps and validation results.

## Commit & Pull Request Guidelines

Git history is unavailable in this checkout, so existing commit conventions cannot be verified. Use concise, imperative subjects such as `Fix footer spacing on mobile`. Keep changes focused. Pull requests should describe the behavior change, link relevant issues, list validation performed, and include screenshots for visual changes.

## Security & Configuration

Keep database credentials, salts, and other secrets out of commits and logs. Treat `wp-config.php` and uploads as environment-specific data. Sanitize inputs, escape output, and check capabilities and nonces for privileged actions.
