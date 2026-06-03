# Security Setup Guide

## Overview
This project now uses environment variables for secure credential management instead of hardcoding database credentials.

## Local Development Setup

### Step 1: Create Your Local `.env` File
```bash
cp .env.example .env
```

### Step 2: Configure Database Credentials
Edit `.env` and add your local database credentials:
```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password_here
DB_NAME=airsell_cargo_db
CARGO_API_KEY=your_rapidapi_key_here
APP_ENV=development
```

### Step 3: Verify .env is in .gitignore
The `.env` file is already in `.gitignore` and will NOT be committed to the repository.

## Production Deployment (Azure)

### Azure Web App Configuration

1. **Navigate to your Web App in Azure Portal**
2. **Go to Settings → Configuration**
3. **Add these Application Settings:**

| Name | Value |
|------|-------|
| DB_HOST | your-db-server.mysql.database.azure.com |
| DB_USER | username@servername |
| DB_PASSWORD | your-secure-password |
| DB_NAME | airsell_cargo_db |
| CARGO_API_KEY | your-rapidapi-key |
| APP_ENV | production |

4. **Click "Save"** and allow the app to restart

### GitHub Actions Secrets (for CI/CD)

1. **Go to Repository Settings → Secrets and variables → Actions**
2. **Add these secrets:**
   - `AZURE_WEBAPP_PUBLISH_PROFILE` (already configured)
   - `DB_HOST`
   - `DB_USER`
   - `DB_PASSWORD`
   - `DB_NAME`
   - `CARGO_API_KEY`

## Database Connection Usage

The `db_connect.php` file now automatically loads environment variables:

```php
require 'db_connect.php';

// $conn is now ready to use
$result = $conn->query("SELECT * FROM shipments");
```

## Security Best Practices

✅ **Never commit credentials** - `.env` is in `.gitignore`
✅ **Use environment variables** - Different configs for dev/prod
✅ **Log errors securely** - User-friendly messages, detailed logs in server
✅ **UTF-8 charset** - Prevents encoding issues
✅ **Proper error handling** - HTTP 500 on connection failure

## Troubleshooting

### "Database connection error. Please contact administrator."
- Check server error logs: `error_log` or Azure Application Insights
- Verify `.env` file exists in project root
- Verify credentials in `.env` are correct
- Check database server is running/accessible

### Credentials Not Loading
- Ensure `.env` file is in the project root directory
- Use `getenv('DB_HOST')` to verify variable is loaded
- Check file permissions (readable by web server)

## References
- [Environment Variables in PHP](https://www.php.net/manual/en/function.getenv.php)
- [Azure App Service Configuration](https://learn.microsoft.com/en-us/azure/app-service/configure-common)
- [mysqli Security](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
