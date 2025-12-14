# Contributing to Sample Data Generator

Thank you for your interest in contributing to Sample Data Generator! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

1. Check if the bug has already been reported in [Issues](https://github.com/yourusername/sample-data-generator/issues)
2. If not, create a new issue with:
   - Clear title and description
   - Steps to reproduce
   - Expected vs actual behavior
   - WordPress and WooCommerce versions
   - PHP version

### Suggesting Features

1. Check existing issues for similar suggestions
2. Create a new issue with:
   - Clear description of the feature
   - Use cases and benefits
   - Possible implementation approach

### Pull Requests

1. Fork the repository
2. Create a new branch: `git checkout -b feature/your-feature-name`
3. Make your changes
4. Follow WordPress coding standards
5. Test your changes thoroughly
6. Commit with clear messages: `git commit -m "Add feature: description"`
7. Push to your fork: `git push origin feature/your-feature-name`
8. Submit a pull request

## Development Setup

```bash
# Clone your fork
git clone https://github.com/yourusername/sample-data-generator.git
cd sample-data-generator

# Install dependencies
npm install

# Make changes to source files
# admin/css/admin.css
# admin/js/admin.js

# Test your changes
grunt minify
```

## Coding Standards

- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use meaningful variable and function names
- Add comments only where necessary
- Keep functions focused and concise
- Sanitize and validate all user inputs
- Escape all outputs

## Testing

- Test with latest WordPress version
- Test with and without WooCommerce
- Test in different browsers
- Verify no PHP errors or warnings
- Check JavaScript console for errors

## Questions?

Feel free to open an issue for any questions about contributing!
