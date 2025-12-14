# Development Guide

This document provides instructions for developers working on the Sample Data Generator plugin.

## Prerequisites

- Node.js (v14 or higher)
- npm (v6 or higher)

## Setup

1. Install dependencies:
```bash
npm install
```

## Grunt Tasks

### Minify Assets

Minify CSS and JavaScript files:

```bash
grunt minify
```

This will create:
- `admin/css/admin.min.css` from `admin/css/admin.css`
- `admin/js/admin.min.js` from `admin/js/admin.js`

The plugin automatically loads minified files if they exist, otherwise falls back to the original files.

### Create Release Package

Create a production-ready ZIP file:

```bash
grunt release
```

This will:
1. Minify all CSS and JS files
2. Create a clean copy of the plugin in the `release/` directory
3. Exclude development files (node_modules, Gruntfile.js, etc.)
4. Include only minified CSS/JS files (not the source files)
5. Create a ZIP file: `release/sample-data-generator-{version}.zip`

The ZIP file is ready for distribution and WordPress.org submission.

### Default Task

Running `grunt` without arguments runs the minify task:

```bash
grunt
```

## File Structure

```
sample-data-generator/
├── admin/
│   ├── css/
│   │   ├── admin.css          # Source CSS
│   │   └── admin.min.css      # Minified CSS (generated)
│   └── js/
│       ├── admin.js           # Source JavaScript
│       └── admin.min.js       # Minified JS (generated)
├── includes/                  # PHP classes
├── release/                   # Release packages (generated)
├── .gitignore                 # Git ignore rules
├── Gruntfile.js              # Grunt configuration
├── package.json              # NPM dependencies
└── sample-data-generator.php # Main plugin file
```

## Development Workflow

1. Make changes to source files:
   - Edit `admin/css/admin.css` for styles
   - Edit `admin/js/admin.js` for scripts
   - Edit PHP files in `includes/`

2. Test your changes:
   - The plugin loads source files during development
   - Minified files are only loaded if they exist

3. Before committing:
   - Run `grunt minify` to generate minified files
   - Test with minified files

4. Creating a release:
   - Update version in `package.json`
   - Update version in `sample-data-generator.php`
   - Run `grunt release`
   - Upload the ZIP from `release/` directory

## Best Practices

- **Don't commit minified files** to version control (they're gitignored)
- **Always test** with both source and minified files
- **Keep source files** clean and well-formatted
- **Update version numbers** in both `package.json` and the main plugin file before release

## Troubleshooting

### Grunt command not found

Install Grunt CLI globally:
```bash
npm install -g grunt-cli
```

### Minified files not loading

Clear the minified files and regenerate:
```bash
rm admin/css/*.min.css admin/js/*.min.js
grunt minify
```

### Release ZIP is too large

Check the exclusion patterns in `Gruntfile.js` under the `copy:release` task.
