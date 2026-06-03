# Deployment Guide

## 🚀 Quick Deployment

### Local Development
```bash
# Clone repository
git clone https://github.com/Airsell-Cargo/airsell-web-portal.git
cd airsell-web-portal

# Setup environment
cp .env.example .env
# Edit .env and add CARGO_API_KEY

# Run local server (PHP 7.4+)
php -S localhost:8000

# Visit http://localhost:8000/tracker.php?piece_id=TEST123
```

### Production Deployment (Apache)

#### Step 1: Server Preparation
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install dependencies
sudo apt install -y php7.4 php7.4-cli libapache2-mod-php7.4 git

# Enable Apache modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo systemctl restart apache2
```

#### Step 2: Clone Repository
```bash
sudo mkdir -p /var/www/airsell-cargo
cd /var/www/airsell-cargo
sudo git clone https://github.com/Airsell-Cargo/airsell-web-portal.git .

# Set permissions
sudo chown -R www-data:www-data /var/www/airsell-cargo
sudo chmod 755 /var/www/airsell-cargo
sudo chmod 644 /var/www/airsell-cargo/*.php
sudo chmod 644 /var/www/airsell-cargo/*.html
```

#### Step 3: Configure Environment
```bash
sudo cp .env.example .env
sudo nano .env  # Edit and add CARGO_API_KEY
sudo chmod 600 .env  # Restrict permissions
```

#### Step 4: Configure Apache Virtual Host
```bash
sudo nano /etc/apache2/sites-available/airsell-cargo.conf
```

Add:
```apache
<VirtualHost *:80>
    ServerName cargo.example.com
    ServerAlias www.cargo.example.com
    DocumentRoot /var/www/airsell-cargo

    <Directory /var/www/airsell-cargo>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/airsell-cargo-error.log
    CustomLog ${APACHE_LOG_DIR}/airsell-cargo-access.log combined

    # Redirect HTTP to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

# HTTPS Configuration
<VirtualHost *:443>
    ServerName cargo.example.com
    ServerAlias www.cargo.example.com
    DocumentRoot /var/www/airsell-cargo

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/airsell-cargo.crt
    SSLCertificateKeyFile /etc/ssl/private/airsell-cargo.key

    <Directory /var/www/airsell-cargo>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Content-Security-Policy "default-src 'self'"

    ErrorLog ${APACHE_LOG_DIR}/airsell-cargo-error.log
    CustomLog ${APACHE_LOG_DIR}/airsell-cargo-access.log combined
</VirtualHost>
```

#### Step 5: Enable Site and SSL
```bash
sudo a2ensite airsell-cargo.conf
sudo a2enmod ssl

# Optional: Use Let's Encrypt for free SSL
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d cargo.example.com

sudo apache2ctl configtest  # Should return "Syntax OK"
sudo systemctl restart apache2
```

#### Step 6: Configure PHP
```bash
sudo nano /etc/php/7.4/apache2/php.ini
```

Set:
```ini
display_errors = Off
error_log = /var/log/php-errors.log
log_errors = On
error_reporting = E_ALL & ~E_NOTICE
date.timezone = "UTC"
```

```bash
sudo systemctl restart apache2
```

### Production Deployment (Nginx)

#### Step 1: Install Dependencies
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx php7.4-fpm php7.4-cli git
```

#### Step 2: Clone Repository
```bash
sudo mkdir -p /var/www/airsell-cargo
cd /var/www/airsell-cargo
sudo git clone https://github.com/Airsell-Cargo/airsell-web-portal.git .

sudo chown -R www-data:www-data /var/www/airsell-cargo
sudo chmod 755 /var/www/airsell-cargo
```

#### Step 3: Configure Nginx
```bash
sudo nano /etc/nginx/sites-available/airsell-cargo
```

Add:
```nginx
# HTTP to HTTPS redirect
server {
    listen 80;
    server_name cargo.example.com www.cargo.example.com;
    return 301 https://$server_name$request_uri;
}

# HTTPS Server
server {
    listen 443 ssl http2;
    server_name cargo.example.com www.cargo.example.com;

    ssl_certificate /etc/ssl/certs/airsell-cargo.crt;
    ssl_certificate_key /etc/ssl/private/airsell-cargo.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Content-Security-Policy "default-src 'self'" always;

    root /var/www/airsell-cargo;
    index index.html index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.env {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/airsell-cargo /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Post-Deployment Verification

```bash
# Test PHP is working
curl -I https://cargo.example.com/tracker.php

# Test tracking endpoint
curl "https://cargo.example.com/tracker.php?piece_id=TEST123"

# Check logs
sudo tail -f /var/log/php-errors.log
sudo tail -f /var/log/apache2/airsell-cargo-error.log  # Apache
sudo tail -f /var/log/nginx/error.log  # Nginx
```

## Monitoring & Maintenance

### Daily Checks
```bash
# Monitor error logs
tail -f /var/log/php-errors.log | grep -i error

# Monitor API usage
grep "tracker.php" /var/log/apache2/access.log | wc -l

# Check disk space
df -h /var/www/airsell-cargo
```

### Weekly Tasks
- Review API response times
- Check for failed requests (500 errors)
- Verify backups are running

### Monthly Tasks
- Review and rotate API keys
- Update PHP and dependencies
- Audit access logs for suspicious activity

### Quarterly Tasks
- Security assessment
- Performance optimization review
- Disaster recovery testing

## Scaling Considerations

### For High Traffic
1. **Implement caching** (Redis/Memcached)
2. **Load balancing** (Nginx/HAProxy)
3. **CDN** for static assets (CloudFlare)
4. **Database optimization** if applicable

### For Multiple Instances
```bash
# Use shared .env configuration management
# Example: AWS Systems Manager Parameter Store, HashiCorp Vault

# Load balance tracking requests
# Example: Use Nginx upstream with multiple PHP-FPM pools
```

## Rollback Procedures

```bash
# View commit history
git log --oneline

# Rollback to previous version
git reset --hard <commit-hash>
sudo systemctl restart apache2  # or nginx

# Verify service is running
curl https://cargo.example.com/tracker.php
```

## Troubleshooting

### "API key not found" Error
```bash
# Verify .env file exists
ls -la /var/www/airsell-cargo/.env

# Check environment variable
php -r "echo getenv('CARGO_API_KEY');"

# Reload PHP-FPM
sudo systemctl restart php7.4-fpm
```

### "Permission Denied" Error
```bash
# Fix file permissions
sudo chown -R www-data:www-data /var/www/airsell-cargo
sudo chmod 644 /var/www/airsell-cargo/.env
```

### "502 Bad Gateway" (Nginx)
```bash
# Check PHP-FPM status
systemctl status php7.4-fpm

# Restart PHP-FPM
sudo systemctl restart php7.4-fpm
```

---
For deployment support: **airsellcargo@gmail.com**
