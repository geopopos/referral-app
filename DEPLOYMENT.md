# Laravel Referral App - Deployment Guide

This guide will help you deploy your Laravel Referral App to a Digital Ocean VPS using GitHub Actions for automated CI/CD.

## 🚀 Quick Start

### Prerequisites
- Digital Ocean VPS with Ubuntu 22.04
- GitHub repository for your Laravel app
- SSH access to your VPS

### Step 1: Set Up Your VPS

1. **SSH into your VPS:**
   ```bash
   ssh root@138.197.67.195
   ```

2. **Download and run the setup script:**
   ```bash
   wget https://raw.githubusercontent.com/your-username/your-repo/main/scripts/server-setup.sh
   chmod +x server-setup.sh
   sudo bash server-setup.sh
   ```

3. **Get the SSH private key:**
   ```bash
   cat /home/deploy/deployment-info.txt
   ```
   Copy the SSH private key from this file.

### Step 2: Configure GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions, and add these secrets:

| Secret Name | Value |
|-------------|-------|
| `HOST` | `138.197.67.195` |
| `USERNAME` | `deploy` |
| `SSH_PRIVATE_KEY` | The SSH private key from deployment-info.txt |
| `ENV_FILE` | Contents of your production .env file (see below) |

### Step 3: Configure Your Production Environment

Copy the contents of `.env.production` and customize it for your needs:

```env
APP_NAME="Volume Up Referral App"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=http://138.197.67.195

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=referral_app
DB_USERNAME=referral_user
DB_PASSWORD=secure_password_123!

# Configure your mail settings
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@volumeupagency.com"
MAIL_FROM_NAME="Volume Up Agency"

# Other settings...
```

**Important:** Generate a new APP_KEY by running `php artisan key:generate --show` locally and add it to your ENV_FILE secret.

### Step 4: Deploy

1. **Push to main branch:**
   ```bash
   git add .
   git commit -m "Initial deployment setup"
   git push origin main
   ```

2. **Monitor the deployment:**
   - Go to your GitHub repository → Actions
   - Watch the deployment workflow run
   - Check for any errors in the logs

3. **Verify deployment:**
   - Visit `http://138.197.67.195` in your browser
   - You should see your Laravel application

## 🔧 Server Configuration Details

### What the Setup Script Installs

- **Nginx** - Web server
- **PHP 8.2** - With all required extensions for Laravel
- **PostgreSQL** - Database server
- **Redis** - Cache and queue backend
- **Supervisor** - Queue worker management
- **Node.js 18** - For asset compilation
- **Composer** - PHP dependency manager
- **Certbot** - For SSL certificates (when you add a domain)
- **fail2ban** - Security protection
- **UFW Firewall** - Basic firewall protection

### Directory Structure

```
/var/www/referral-app/
├── current -> releases/20250713114500/  # Symlink to current release
├── releases/                            # All deployment releases
│   ├── 20250713114500/                 # Timestamped releases
│   └── 20250713120000/
└── shared/                             # Shared files between releases
    └── storage/                        # Laravel storage directory
        ├── logs/
        ├── framework/
        └── app/
```

### Services Configuration

- **Nginx:** Configured with security headers, gzip compression, and Laravel-specific rules
- **PHP-FPM:** Optimized pool configuration for production
- **Supervisor:** Manages 2 Laravel queue workers for webhook processing
- **PostgreSQL:** Database with dedicated user and database
- **Redis:** Used for caching, sessions, and queue backend

## 🔒 Security Features

### Firewall (UFW)
- Only ports 22 (SSH), 80 (HTTP), and 443 (HTTPS) are open
- All other ports are blocked by default

### fail2ban
- Protects against brute force attacks
- Automatically bans IPs after failed login attempts
- Monitors SSH, Nginx, and other services

### SSH Security
- Password authentication disabled
- Only SSH key authentication allowed
- Dedicated deploy user with sudo privileges

### Application Security
- Environment variables stored securely
- Database access restricted to localhost
- Security headers configured in Nginx
- Laravel security best practices applied

## 📊 Monitoring & Maintenance

### Queue Workers
Queue workers are managed by Supervisor and will automatically restart if they fail:

```bash
# Check queue worker status
sudo supervisorctl status laravel-worker:*

# Restart queue workers
sudo supervisorctl restart laravel-worker:*

# View queue worker logs
tail -f /var/www/referral-app/shared/storage/logs/worker.log
```

### Database Backups
Automated daily backups are configured:

- **Schedule:** Every day at 2 AM
- **Location:** `/var/backups/referral-app/`
- **Retention:** 7 days
- **Includes:** Database dump and application files

```bash
# Manual backup
sudo /usr/local/bin/backup-app.sh

# View backups
ls -la /var/backups/referral-app/
```

### Log Files
Important log locations:

- **Laravel logs:** `/var/www/referral-app/shared/storage/logs/`
- **Nginx logs:** `/var/log/nginx/`
- **PHP-FPM logs:** `/var/log/php8.2-fpm.log`
- **PostgreSQL logs:** `/var/log/postgresql/`

## 🌐 Adding a Domain Name

When you're ready to add a domain name:

1. **Point your domain to the server:**
   - Create an A record pointing to `138.197.67.195`

2. **Update Nginx configuration:**
   ```bash
   sudo nano /etc/nginx/sites-available/referral-app
   # Change server_name from IP to your domain
   ```

3. **Get SSL certificate:**
   ```bash
   sudo certbot --nginx -d yourdomain.com
   ```

4. **Update your .env file:**
   - Change `APP_URL` to your domain
   - Update `SANCTUM_STATEFUL_DOMAINS`
   - Set `SESSION_SECURE_COOKIE=true`

## 🔄 Deployment Process

### How Deployments Work

1. **GitHub Actions triggers** on push to main branch
2. **Code is tested** with PHPUnit
3. **Dependencies installed** (Composer & npm)
4. **Assets compiled** (CSS/JS)
5. **Code uploaded** to server
6. **New release created** with timestamp
7. **Database migrations** run automatically
8. **Symlink updated** to new release (zero-downtime)
9. **Services restarted** (Nginx, queue workers)
10. **Health check** performed
11. **Old releases cleaned up** (keeps last 5)

### Manual Deployment Commands

If you need to deploy manually:

```bash
# SSH to server
ssh deploy@138.197.67.195

# Navigate to app directory
cd /var/www/referral-app/current

# Pull latest changes (if needed)
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
sudo supervisorctl restart laravel-worker:*
```

## 🐛 Troubleshooting

### Common Issues

**1. Deployment fails with permission errors:**
```bash
# Fix permissions
sudo chown -R deploy:www-data /var/www/referral-app
sudo chmod -R 755 /var/www/referral-app
sudo chmod -R 775 /var/www/referral-app/shared/storage
```

**2. Queue workers not processing jobs:**
```bash
# Check supervisor status
sudo supervisorctl status

# Restart supervisor
sudo systemctl restart supervisor

# Check logs
tail -f /var/www/referral-app/shared/storage/logs/worker.log
```

**3. Database connection issues:**
```bash
# Check PostgreSQL status
sudo systemctl status postgresql

# Test database connection
sudo -u postgres psql -d referral_app -c "SELECT version();"
```

**4. Nginx not serving the site:**
```bash
# Check Nginx status
sudo systemctl status nginx

# Test Nginx configuration
sudo nginx -t

# Check error logs
sudo tail -f /var/log/nginx/error.log
```

### Health Checks

```bash
# Check all services
sudo systemctl status nginx php8.2-fpm postgresql redis-server supervisor

# Check application
curl -I http://138.197.67.195

# Check queue workers
sudo supervisorctl status laravel-worker:*

# Check disk space
df -h

# Check memory usage
free -h
```

## 📞 Support

If you encounter issues:

1. Check the GitHub Actions logs for deployment errors
2. SSH to the server and check service logs
3. Verify all GitHub secrets are correctly set
4. Ensure your .env file has all required variables

## 🔄 Updates and Maintenance

### Regular Maintenance Tasks

**Weekly:**
- Check server logs for errors
- Monitor disk space usage
- Review backup files

**Monthly:**
- Update system packages: `sudo apt update && sudo apt upgrade`
- Review security logs
- Check SSL certificate expiration (if using domain)

**As Needed:**
- Update Laravel dependencies
- Review and update environment variables
- Scale queue workers if needed

### Scaling Considerations

As your application grows, consider:

- **Database:** Migrate to managed PostgreSQL service
- **Redis:** Use managed Redis service
- **Load Balancer:** Add multiple app servers
- **CDN:** Use CloudFlare or similar for static assets
- **Monitoring:** Add application monitoring (New Relic, etc.)

---

## 🎉 You're All Set!

Your Laravel Referral App is now deployed with:
- ✅ Zero-downtime deployments
- ✅ Automated database migrations
- ✅ Queue worker management
- ✅ Daily backups
- ✅ Security hardening
- ✅ SSL ready (when you add a domain)

Every push to your main branch will automatically deploy your changes!
