# Laravel SafeMailer

A Laravel package for secure email attachments with expiration functionality.

## Installation

1. Install the package via Composer:

```bash
composer require safemailer/safemailer
```

2. Add your SafeMailer API key to your `.env` file:

```env
SAFEMAILER_API_KEY=your_api_key_here
```

3. Change MAIL_MAILER to be "safemailer" in your .env file


## Advanced Usage

1. To define a expiration for the email:

```php
$expiresAt = 'one_time'; // or 'never' ('never' is default)

Mail::mailer('safemailer')
    ->to($user)
    ->send(new ExampleMail($expiresAt));
```


### Available Methods

FUTURE FEATURE:
You can use all standard Laravel Mail attachment methods - SafeMailer will automatically secure them:

```php
// Basic attachment
->attach('/path/to/file.pdf')

// With custom filename
->attach('/path/to/file.pdf', [
    'as' => 'document.pdf'
])

// Attach multiple files
->attach('/path/to/file1.pdf')
->attach('/path/to/file2.pdf')
```



## Security

- All attachments are stored securely on SafeMailer's servers
- Files are automatically deleted after the expiration period
- Each attachment gets a unique, secure download link
- Downloads can be tracked and monitored

## Best Practices

1. Always set appropriate expiration times for sensitive documents
2. Use environment variables for API keys
3. Monitor attachment downloads through the SafeMailer dashboard
4. Keep your SafeMailer package updated to the latest version

## Troubleshooting

Common issues and solutions:

1. **API Key Issues**
   - Ensure your API key is correctly set in the `.env` file
   - Check if the API key is active in your SafeMailer dashboard

2. **File Upload Errors**
   - Verify file permissions
   - Check file size limits
   - Ensure proper file paths

3. **Configuration Issues**
   - Run `php artisan config:clear` after updating configuration
   - Verify published config file exists

## Support

For additional support:
- Submit issues on GitHub
- Contact support@safemailer.io
