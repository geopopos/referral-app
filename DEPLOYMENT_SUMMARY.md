# 🚀 Laravel Referral App - Complete Deployment Setup

## 📋 What's Been Created

Your Laravel Referral App now has a complete production deployment setup with PostgreSQL! Here's what has been configured:

### 🔧 Deployment Files Created

1. **`.github/workflows/deploy.yml`** - GitHub Actions CI/CD pipeline
2. **`scripts/server-setup.sh`** - VPS setup script for Ubuntu 22.04
3. **`scripts/prepare-deployment.sh`** - Local preparation script
4. **`.env.production`** - Production environment template
5. **`DEPLOYMENT.md`** - Complete deployment guide

### 🎯 Key Features

- ✅ **Zero-downtime deployments** using symlinks
- ✅ **PostgreSQL database** with automated setup
- ✅ **Redis** for caching, sessions, and queues
- ✅ **Supervisor** managing Laravel queue workers
- ✅ **Nginx** with security headers and optimization
- ✅ **Automated backups** (daily at 2 AM)
- ✅ **Security hardening** (UFW firewall, fail2ban, SSH keys)
- ✅ **SSL ready** (easily add domain + Let's Encrypt)

## 🚀 Quick Deployment Steps

### Step 1: Prepare Locally
```bash
cd referral-app
chmod +x scripts/prepare-deployment.sh
./scripts/prepare-deployment.sh
```

### Step 2: Set Up Your VPS (138.197.67.195)
**⚠️ IMPORTANT: Run this ON YOUR VPS, not locally!**

```bash
# SSH to your VPS as root
ssh root@138.197.67.195

# Download and run setup script
wget https://raw.githubusercontent.com/your-username/your-repo/main/scripts/server-setup.sh
chmod +x server-setup.sh
sudo bash server-setup.sh

# Get SSH private key for GitHub
cat /home/deploy/deployment-info.txt
```

**Note:** If you try to run the server setup script locally (on macOS/Windows), it will detect this and show an error message directing you to run it on your VPS instead.

**Supported Operating Systems:**
- Ubuntu 20.04/22.04 LTS (Recommended)
- Debian 11/12
- CentOS 8/9
- Rocky Linux 8/9
- AlmaLinux 8/9

### Step 3: Configure GitHub Secrets
Go to GitHub → Settings → Secrets and variables → Actions:

| Secret Name | Value |
|-------------|-------|
| `HOST` | `138.197.67.195` |
| `USERNAME` | `deploy` |
| `SSH_PRIVATE_KEY` | SSH private key from deployment-info.txt |
| `ENV_FILE` | Contents of your customized .env.production |

### Step 4: Deploy
```bash
git add .
git commit -m "Add deployment configuration"
git push origin main
```

## 🗄️ Database Configuration

Your app is configured for **PostgreSQL** with these settings:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=referral_app
DB_USERNAME=referral_user
DB_PASSWORD=secure_password_123!
```

## 🔄 Queue System

Your webhook system will work perfectly with:
- **Redis** as queue backend
- **2 Supervisor workers** processing jobs
- **Automatic restart** after deployments
- **Retry mechanisms** for failed webhooks

## 🌐 Access Your App

After deployment, your app will be available at:
- **HTTP:** http://138.197.67.195
- **Admin Panel:** http://138.197.67.195/admin
- **API:** http://138.197.67.195/api

## 📧 Email Configuration

Update your `.env.production` with your email provider:

```env
# For Gmail/Google Workspace
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# For Mailgun
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-secret

# For SendGrid
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

## 🔒 Security Features

- **Firewall:** Only ports 22, 80, 443 open
- **fail2ban:** Automatic IP banning for failed attempts
- **SSH Keys:** Password authentication disabled
- **Environment Variables:** Securely stored in GitHub Secrets
- **Database:** Access restricted to localhost
- **Headers:** Security headers configured in Nginx

## 📊 Monitoring

### Check Deployment Status
```bash
# SSH to server
ssh deploy@138.197.67.195

# Check services
sudo systemctl status nginx php8.2-fpm postgresql redis-server supervisor

# Check queue workers
sudo supervisorctl status laravel-worker:*

# View logs
tail -f /var/www/referral-app/shared/storage/logs/laravel.log
```

### View Backups
```bash
ls -la /var/backups/referral-app/
```

## 🌟 Adding a Domain

When you get a domain name:

1. **Point domain to server:** Create A record → 138.197.67.195
2. **Update Nginx:** Change server_name in `/etc/nginx/sites-available/referral-app`
3. **Get SSL:** `sudo certbot --nginx -d yourdomain.com`
4. **Update .env:** Change APP_URL and SANCTUM_STATEFUL_DOMAINS

## 🔧 Customization

### Scale Queue Workers
Edit `/etc/supervisor/conf.d/laravel-worker.conf` and change `numprocs=2` to desired number.

### Database Optimization
For high traffic, consider:
- Upgrading to Digital Ocean Managed PostgreSQL
- Adding read replicas
- Implementing database connection pooling

### Performance Monitoring
Consider adding:
- New Relic or Datadog for application monitoring
- CloudFlare for CDN and DDoS protection
- Redis Sentinel for high availability

## 🆘 Troubleshooting

### Common Issues

**Deployment fails:**
- Check GitHub Actions logs
- Verify all secrets are set correctly
- Ensure SSH key is properly formatted

**App not loading:**
```bash
# Check Nginx
sudo systemctl status nginx
sudo nginx -t

# Check PHP-FPM
sudo systemctl status php8.2-fpm

# Check logs
sudo tail -f /var/log/nginx/error.log
```

**Database issues:**
```bash
# Check PostgreSQL
sudo systemctl status postgresql
sudo -u postgres psql -d referral_app -c "SELECT version();"
```

**Queue not processing:**
```bash
# Check workers
sudo supervisorctl status laravel-worker:*
sudo supervisorctl restart laravel-worker:*
```

## 📞 Support

For issues:
1. Check the detailed `DEPLOYMENT.md` guide
2. Review GitHub Actions logs
3. SSH to server and check service logs
4. Verify environment variables

## 🎉 You're Ready!

Your Laravel Referral App is now ready for production deployment with:
- Professional-grade infrastructure
- Automated CI/CD pipeline
- Security best practices
- Monitoring and backup systems
- Scalable architecture

Every push to your main branch will automatically deploy your changes with zero downtime!

---

**Server IP:** 138.197.67.195  
**Database:** PostgreSQL  
**Queue System:** Redis + Supervisor  
**Web Server:** Nginx + PHP 8.2  
**Deployment:** GitHub Actions
