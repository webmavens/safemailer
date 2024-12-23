# Laravel SafeMailer

A Laravel package for secure email attachments with expiration functionality.

## Installation

1. Install the package via Composer:

```bash
composer require safemailer/laravel-safemailer
```

2. Add your SafeMailer API key to your `.env` file:

```env
SAFEMAILER_API_KEY=your_api_key_here
```

3. Publish the configuration file:

```bash
php artisan vendor:publish --provider="SafeMailer\LaravelSafeMailer\SafeMailerServiceProvider"
```

This will create a `config/safemailer.php` file in your project.

## Configuration

The `safemailer.php` config file allows you to customize:

```php
return [
    'api_key' => env('SAFEMAILER_API_KEY'),
];
```

## Usage

### Basic Usage

1. Configure the SafeMailer transport in your `config/mail.php`:

```php
'mailers' => [
    'safemailer' => [
        'transport' => 'safemailer',
    ],
    // ... other mailers
],
```

2. Create a Mailable class:

```php
use Illuminate\Mail\Mailable;

class ExampleMail extends Mailable
{
    public function build()
    {
        return $this->view('emails.example')
                    ->attach('/path/to/file.pdf')
                    ->subject('Your Secure Document');
    }
}
```

3. Send the email using the SafeMailer transport:

```php
$expiresAt = 'one_time'; // or 'never'

Mail::mailer('safemailer')
    ->to($user)
    ->send(new ExampleMail($expiresAt));
```

The SafeMailer transport will automatically handle secure file uploads and generate secure download links for any attachments.

### Available Methods

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

### Using with Laravel's Mail Facade

```php
use Illuminate\Support\Facades\Mail;

$expiresAt = 'one_time'; // or 'never'

Mail::mailer('safemailer')
    ->to($user)
    ->send(new ExampleMail($expiresAt));
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
