# Deployment Guide Koperasi Merah Putih

## Prerequisites

- PHP 8.0+ dengan extensions: mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json, bcmath, gd
- MySQL 8.0+ 
- Web Server (Apache/Nginx)
- Composer
- SSL Certificate (untuk production)

## Production Deployment Steps

### 1. Server Setup

```bash
# Update server
sudo apt update && sudo apt upgrade -y

# Install PHP extensions
sudo apt install php8.1-mbstring php8.1-xml php8.1-bcmath php8.1-gd php8.1-curl php8.1-zip

# Install dan konfigurasi MySQL
sudo mysql_secure_installation
```

### 2. Application Setup

```bash
# Clone atau upload project
cd /var/www/kop_merahputih

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
sudo chown -R www-data:www-data /var/www/kop_merahputih
sudo chmod -R 755 /var/www/kop_merahputih
sudo chmod -R 777 /var/www/kop_merahputih/storage
sudo chmod -R 777 /var/www/kop_merahputih/bootstrap/cache
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env
nano .env
```

Edit `.env` untuk production:
```env
APP_NAME="Koperasi Merah Putih"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kop_merahputih_prod
DB_USERNAME=kop_user
DB_PASSWORD=secure_password

LOG_CHANNEL=stack
LOG_LEVEL=warning

# Email configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### 4. Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE kop_merahputih_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kop_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON kop_merahputih_prod.* TO 'kop_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force

# Seed data
php artisan db:seed --force
```

### 5. Application Optimization

```bash
# Generate key
php artisan key:generate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. Web Server Configuration

#### Apache Configuration

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/kop_merahputih/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/kop_merahputih>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/kop_merahputih_error.log
    CustomLog ${APACHE_LOG_DIR}/kop_merahputih_access.log combined
</VirtualHost>
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/kop_merahputih/public;
    index index.php;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 7. Security Hardening

```bash
# Set secure permissions
sudo chmod 600 .env
sudo chmod 600 storage/*.log

# Configure firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

# Install fail2ban
sudo apt install fail2ban
sudo systemctl enable fail2ban
```

### 8. Monitoring Setup

```bash
# Create log rotation
sudo nano /etc/logrotate.d/kop_merahputih
```

Content untuk logrotate:
```
/var/www/kop_merahputih/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

### 9. Backup Strategy

```bash
# Create backup script
nano /home/user/backup_kop_merahputih.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/user/backups"
APP_DIR="/var/www/kop_merahputih"

# Database backup
mysqldump -u kop_user -p'secure_password' kop_merahputih_prod > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz $APP_DIR/storage

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
# Make executable and add to crontab
chmod +x /home/user/backup_kop_merahputih.sh
crontab -e
# Add: 0 2 * * * /home/user/backup_kop_merahputih.sh
```

### 10. Performance Optimization

```bash
# Install Redis untuk cache
sudo apt install redis-server
sudo systemctl enable redis-server

# Update .env untuk Redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 11. Final Verification

```bash
# Test application
curl -I https://yourdomain.com

# Check logs
tail -f storage/logs/laravel.log

# Verify database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

## Post-Deployment Checklist

- [ ] SSL certificate valid
- [ ] All routes working
- [ ] Database connection successful
- [ ] File permissions correct
- [ ] Email sending working
- [ ] Backup system running
- [ ] Monitoring active
- [ ] Security headers present
- [ ] Cache optimization enabled
- [ ] Error logging configured

## Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check storage permissions
   - Verify .env configuration
   - Check error logs: `tail storage/logs/laravel.log`

2. **Database Connection Failed**
   - Verify database credentials
   - Check MySQL service status
   - Test connection: `php artisan tinker`

3. **Asset Not Loading**
   - Run `php artisan storage:link`
   - Clear cache: `php artisan optimize:clear`

4. **Email Not Sending**
   - Verify mail configuration
   - Check SMTP credentials
   - Test with `php artisan tinker`

## Maintenance Commands

```bash
# Weekly maintenance
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Update dependencies
composer update
npm update

# Security updates
sudo apt update && sudo apt upgrade
```

## Support

For deployment issues, contact:
- Email: support@kopmerahputih.desa.id
- Documentation: Check README.md
