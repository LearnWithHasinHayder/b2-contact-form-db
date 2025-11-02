# Basic Contact Form Plugin

A dedicated WordPress plugin for contact forms with AJAX submission and custom post type storage.

## Features

- AJAX form submission (no page refresh)
- Multiple form styles (normal, elegant, amazing, modern, minimal)
- Alignment options (left, center, right)
- Custom post type storage ('support')
- Severity taxonomy for categorizing submissions
- Admin columns for email and IP tracking
- Sortable and searchable admin columns
- Security features (nonces, sanitization)

## Installation

1. Upload the `basic-contact-form` folder to `/wp-content/plugins/`
2. Activate the plugin through WordPress admin
3. The 'support' custom post type and 'severity' taxonomy will be automatically created

## Usage

Add the contact form to any page or post using this shortcode:

```
[basic_contact_form]
```

### Shortcode Attributes

- `style`: Form appearance (normal, elegant, amazing, modern, minimal)
- `align`: Form alignment (left, center, right)

### Examples

```
[basic_contact_form style="modern" align="center"]
[basic_contact_form style="minimal" align="left"]
[basic_contact_form style="amazing" align="right"]
```

## Form Fields

- Name (required)
- Email (required)
- Message (required)
- Severity (dropdown: Low, Medium, High)

## Admin Features

- **Support Posts**: View all form submissions in WordPress admin under "Supports"
- **Custom Columns**: Email and IP address columns
- **Sorting**: Click column headers to sort by email or IP
- **Severity Filter**: Filter submissions by severity level
- **Private Posts**: All submissions are saved as private posts

## File Structure

```
basic-contact-form/
├── basic-contact-form.php          # Main plugin file
├── includes/
│   ├── class-cpt.php              # Custom post type registration
│   └── class-contact-form.php     # Form handling and display
└── assets/
    └── js/
        └── contact-form.js        # AJAX form submission
```

## Security

- Nonce verification for all AJAX requests
- Data sanitization for all form inputs
- User capability checks
- XSS protection with `esc_html()`

## Customization

The plugin includes multiple CSS styles that you can modify in the `render_form()` method of `class-contact-form.php`. Each style has its own CSS block that can be customized.

## Requirements

- WordPress 5.0+
- PHP 7.0+

## Changelog

### 1.0.0
- Initial release
- AJAX form submission
- Multiple styles and alignments
- Custom post type storage
- Admin columns and sorting