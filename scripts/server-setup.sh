#!/bin/bash

# Laravel Referral App - VPS Setup Script
# For Ubuntu 22.04 with Nginx, PHP 8.2, PostgreSQL, Redis
# Run as root: sudo bash server-setup.sh

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
SERVER_IP="138.197.67.195"
APP_NAME="referral-app"
DEPLOY_USER="deploy"
WEB_ROOT="/var/www/$APP_NAME"
DOMAIN_OR_IP="$SERVER_IP"
DB_NAME="referral_app"
DB_USER="referral_user"
DB_PASSWORD="secure_password_123!"

echo -e "${GREEN}🚀 Starting Laravel Referral App VPS Setup${NC}"
echo -e "${YELLOW}Server IP: $SERVER_IP${NC}"
echo -e "${YELLOW}App Name: $APP_NAME${NC}"
echo -e "${YELLOW}Database: PostgreSQL${NC}"

# Detect OS and package manager
OS=""
VER=""

# Try multiple methods to detect OS
if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$NAME
    VER=$VERSION_ID
elif [ -f /etc/redhat-release ]; then
    OS=$(cat /etc/redhat-release)
elif [ -f /etc/debian_version ]; then
    OS="Debian $(cat /etc/debian_version)"
elif [ -f /etc/lsb-release ]; then
    . /etc/lsb-release
    OS=$DISTRIB_DESCRIPTION
elif command -v lsb_release >/dev/null 2>&1; then
    OS=$(lsb_release -d | cut -f2)
elif [ "$(uname)" = "Darwin" ]; then
    OS="macOS $(sw_vers -productVersion)"
elif [ "$(uname)" = "Linux" ]; then
    OS="Unknown Linux Distribution"
else
    OS="$(uname -s)"
fi

if [ -z "$OS" ]; then
    echo -e "${RED}❌ Cannot detect operating system${NC}"
    echo -e "${YELLOW}Please run this script on a supported Linux VPS${NC}"
    exit 1
fi

echo -e "${YELLOW}Detected OS: $OS${NC}"

# Check if this is a supported system
if [[ "$(uname)" = "Darwin" ]]; then
    echo -e "${RED}❌ This script is designed for Linux VPS servers, not macOS${NC}"
    echo -e "${YELLOW}Please run this script on your Ubuntu 22.04 VPS at 138.197.67.195${NC}"
    echo -e "${YELLOW}SSH to your VPS first: ssh root@138.197.67.195${NC}"
    exit 1
fi

if [[ "$(uname)" != "Linux" ]]; then
    echo -e "${RED}❌ This script requires a Linux operating system${NC}"
    echo -e "${YELLOW}Please run this script on your Ubuntu 22.04 VPS${NC}"
    exit 1
fi

# Check if this is Ubuntu/Debian
if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
    PKG_MANAGER="apt"
    PKG_UPDATE="apt update && apt upgrade -y"
    PKG_INSTALL="apt install -y"
    
    # Check if we're root or have sudo
    if [[ $EUID -ne 0 ]]; then
        echo -e "${RED}❌ This script must be run as root or with sudo${NC}"
        echo -e "${YELLOW}Please run: sudo bash server-setup.sh${NC}"
        exit 1
    fi
    
elif [[ "$OS" == *"CentOS"* ]] || [[ "$OS" == *"Red Hat"* ]] || [[ "$OS" == *"Rocky"* ]] || [[ "$OS" == *"AlmaLinux"* ]]; then
    PKG_MANAGER="yum"
    PKG_UPDATE="yum update -y"
    PKG_INSTALL="yum install -y"
    
    if [[ $EUID -ne 0 ]]; then
        echo -e "${RED}❌ This script must be run as root or with sudo${NC}"
        echo -e "${YELLOW}Please run: sudo bash server-setup.sh${NC}"
        exit 1
    fi
    
else
    echo -e "${RED}❌ Unsupported operating system: $OS${NC}"
    echo -e "${YELLOW}This script is designed for Ubuntu 22.04 LTS${NC}"
    echo -e "${YELLOW}Please use an Ubuntu 22.04 VPS for best compatibility${NC}"
    echo -e "${YELLOW}Supported systems:${NC}"
    echo -e "  - Ubuntu 20.04/22.04"
    echo -e "  - Debian 11/12"
    echo -e "  - CentOS 8/9"
    echo -e "  - Rocky Linux 8/9"
    echo -e "  - AlmaLinux 8/9"
    exit 1
fi

# Verify we have the required package manager
if ! command -v $PKG_MANAGER &> /dev/null; then
    echo -e "${RED}❌ Package manager '$PKG_MANAGER' not found${NC}"
    echo -e "${YELLOW}Please ensure you're running this on a supported Linux distribution${NC}"
    exit 1
fi

# Update system
echo -e "${GREEN}📦 Updating system packages...${NC}"
eval $PKG_UPDATE

# Install essential packages
echo -e "${GREEN}📦 Installing essential packages...${NC}"
if [[ "$PKG_MANAGER" == "apt" ]]; then
    $PKG_INSTALL curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    $PKG_INSTALL curl wget git unzip epel-release
fi

# Install Nginx
echo -e "${GREEN}🌐 Installing Nginx...${NC}"
$PKG_INSTALL nginx

# Install PHP 8.2 and extensions
echo -e "${GREEN}🐘 Installing PHP 8.2 and extensions...${NC}"
if [[ "$PKG_MANAGER" == "apt" ]]; then
    # Add PHP 8.2 repository for Ubuntu/Debian
    add-apt-repository ppa:ondrej/php -y
    apt update
    $PKG_INSTALL php8.2-fpm php8.2-cli php8.2-pgsql php8.2-redis php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath php8.2-soap php8.2-imagick
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    # Install Remi repository for CentOS/RHEL
    yum install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm
    yum module enable php:remi-8.2 -y
    $PKG_INSTALL php php-fpm php-pgsql php-redis php-xml php-curl php-mbstring php-zip php-gd php-intl php-bcmath php-soap
fi

# Install PostgreSQL
echo -e "${GREEN}🐘 Installing PostgreSQL...${NC}"
if [[ "$PKG_MANAGER" == "apt" ]]; then
    $PKG_INSTALL postgresql postgresql-contrib
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    $PKG_INSTALL postgresql postgresql-server postgresql-contrib
    postgresql-setup initdb
fi

# Install Redis
echo -e "${GREEN}📊 Installing Redis...${NC}"
$PKG_INSTALL redis

# Install Supervisor
echo -e "${GREEN}👷 Installing Supervisor...${NC}"
if [[ "$PKG_MANAGER" == "apt" ]]; then
    $PKG_INSTALL supervisor
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    $PKG_INSTALL supervisor python3-pip
    pip3 install supervisor
fi

# Install Composer
echo -e "${GREEN}🎼 Installing Composer...${NC}"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
echo -e "${GREEN}📦 Installing Node.js...${NC}"
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
$PKG_INSTALL nodejs

# Install Certbot for SSL
echo -e "${GREEN}🔒 Installing Certbot...${NC}"
if [[ "$PKG_MANAGER" == "apt" ]]; then
    $PKG_INSTALL certbot python3-certbot-nginx
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    $PKG_INSTALL certbot python3-certbot-nginx
fi

# Install fail2ban for security
echo -e "${GREEN}🛡️  Installing fail2ban...${NC}"
$PKG_INSTALL fail2ban

# Create deploy user
echo -e "${GREEN}👤 Creating deploy user...${NC}"
if ! id "$DEPLOY_USER" &>/dev/null; then
    useradd -m -s /bin/bash $DEPLOY_USER
    usermod -aG sudo $DEPLOY_USER
    echo "$DEPLOY_USER ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers.d/$DEPLOY_USER
fi

# Create SSH directory for deploy user
mkdir -p /home/$DEPLOY_USER/.ssh
chown $DEPLOY_USER:$DEPLOY_USER /home/$DEPLOY_USER/.ssh
chmod 700 /home/$DEPLOY_USER/.ssh

# Create web directory structure
echo -e "${GREEN}📁 Creating web directory structure...${NC}"
mkdir -p $WEB_ROOT/{releases,shared/storage/{logs,framework/{cache,sessions,views},app}}
chown -R $DEPLOY_USER:www-data $WEB_ROOT
chmod -R 755 $WEB_ROOT

# Configure PostgreSQL
echo -e "${GREEN}🐘 Configuring PostgreSQL...${NC}"
systemctl start postgresql
systemctl enable postgresql

# Create database and user
sudo -u postgres psql << EOF
CREATE DATABASE $DB_NAME;
CREATE USER $DB_USER WITH ENCRYPTED PASSWORD '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;
ALTER USER $DB_USER CREATEDB;
\q
EOF

# Configure Redis
echo -e "${GREEN}📊 Configuring Redis...${NC}"
if [[ "$PKG_MANAGER" == "apt" ]]; then
    systemctl enable redis-server
    systemctl start redis-server
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    systemctl enable redis
    systemctl start redis
fi

# Configure PHP-FPM
echo -e "${GREEN}🐘 Configuring PHP-FPM...${NC}"
if [[ "$PKG_MANAGER" == "apt" ]]; then
    PHP_FPM_POOL="/etc/php/8.2/fpm/pool.d/www.conf"
    PHP_FPM_SERVICE="php8.2-fpm"
    PHP_FPM_SOCK="/run/php/php8.2-fpm.sock"
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    PHP_FPM_POOL="/etc/php-fpm.d/www.conf"
    PHP_FPM_SERVICE="php-fpm"
    PHP_FPM_SOCK="/run/php-fpm/www.sock"
fi

cat > $PHP_FPM_POOL << EOF
[www]
user = www-data
group = www-data
listen = $PHP_FPM_SOCK
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 500
EOF

# Configure Nginx
echo -e "${GREEN}🌐 Configuring Nginx...${NC}"
cat > /etc/nginx/sites-available/$APP_NAME << EOF
server {
    listen 80;
    server_name $DOMAIN_OR_IP;
    root $WEB_ROOT/current/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:$PHP_FPM_SOCK;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.(env|git) {
        deny all;
    }

    # Laravel specific
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Security
    location ~ /\.ht {
        deny all;
    }
}
EOF

# Enable the site (handle both Debian and RHEL style configs)
if [ -d "/etc/nginx/sites-available" ]; then
    ln -sf /etc/nginx/sites-available/$APP_NAME /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
else
    # For RHEL-based systems, copy to conf.d
    cp /etc/nginx/sites-available/$APP_NAME /etc/nginx/conf.d/$APP_NAME.conf
fi

# Test Nginx configuration
nginx -t

# Configure Supervisor for Laravel queues
echo -e "${GREEN}👷 Configuring Supervisor for Laravel queues...${NC}"
cat > /etc/supervisor/conf.d/laravel-worker.conf << EOF
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $WEB_ROOT/current/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$DEPLOY_USER
numprocs=2
redirect_stderr=true
stdout_logfile=$WEB_ROOT/shared/storage/logs/worker.log
stdout_logfile_maxbytes=100MB
stdout_logfile_backups=2
EOF

# Configure UFW Firewall (if available)
echo -e "${GREEN}🔥 Configuring Firewall...${NC}"
if command -v ufw &> /dev/null; then
    ufw --force enable
    ufw default deny incoming
    ufw default allow outgoing
    ufw allow ssh
    ufw allow 'Nginx Full'
elif command -v firewall-cmd &> /dev/null; then
    systemctl enable firewalld
    systemctl start firewalld
    firewall-cmd --permanent --add-service=ssh
    firewall-cmd --permanent --add-service=http
    firewall-cmd --permanent --add-service=https
    firewall-cmd --reload
fi

# Configure fail2ban
echo -e "${GREEN}🛡️  Configuring fail2ban...${NC}"
cat > /etc/fail2ban/jail.local << 'EOF'
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true
port = ssh
logpath = %(sshd_log)s
backend = %(sshd_backend)s

[nginx-http-auth]
enabled = true

[nginx-noscript]
enabled = true

[nginx-badbots]
enabled = true

[nginx-noproxy]
enabled = true
EOF

# Start and enable services
echo -e "${GREEN}🔄 Starting and enabling services...${NC}"
systemctl enable nginx
systemctl enable $PHP_FPM_SERVICE
systemctl enable postgresql
systemctl enable supervisor
systemctl enable fail2ban

systemctl start nginx
systemctl start $PHP_FPM_SERVICE
systemctl start postgresql
systemctl start supervisor
systemctl start fail2ban

# Start Redis with correct service name
if [[ "$PKG_MANAGER" == "apt" ]]; then
    systemctl start redis-server
elif [[ "$PKG_MANAGER" == "yum" ]]; then
    systemctl start redis
fi

# Generate SSH key for GitHub Actions
echo -e "${GREEN}🔑 Generating SSH key for GitHub Actions...${NC}"
if [ ! -f /home/$DEPLOY_USER/.ssh/id_rsa ]; then
    sudo -u $DEPLOY_USER ssh-keygen -t rsa -b 4096 -f /home/$DEPLOY_USER/.ssh/id_rsa -N ""
    cat /home/$DEPLOY_USER/.ssh/id_rsa.pub >> /home/$DEPLOY_USER/.ssh/authorized_keys
    chmod 600 /home/$DEPLOY_USER/.ssh/authorized_keys
    chown $DEPLOY_USER:$DEPLOY_USER /home/$DEPLOY_USER/.ssh/authorized_keys
fi

# Create backup script
echo -e "${GREEN}💾 Creating backup script...${NC}"
cat > /usr/local/bin/backup-app.sh << EOF
#!/bin/bash
BACKUP_DIR="/var/backups/$APP_NAME"
DATE=\$(date +%Y%m%d_%H%M%S)

mkdir -p \$BACKUP_DIR

# Backup database
sudo -u postgres pg_dump $DB_NAME > \$BACKUP_DIR/database_\$DATE.sql

# Backup application files
tar -czf \$BACKUP_DIR/files_\$DATE.tar.gz -C $WEB_ROOT/current .

# Keep only last 7 days of backups
find \$BACKUP_DIR -name "*.sql" -mtime +7 -delete
find \$BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: \$DATE"
EOF

chmod +x /usr/local/bin/backup-app.sh

# Add backup to crontab
echo -e "${GREEN}⏰ Setting up automated backups...${NC}"
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/backup-app.sh") | crontab -

# Create deployment info file
cat > /home/$DEPLOY_USER/deployment-info.txt << EOF
=== Laravel Referral App Deployment Info ===

Server IP: $SERVER_IP
App Name: $APP_NAME
Deploy User: $DEPLOY_USER
Web Root: $WEB_ROOT
Database: PostgreSQL
Database Name: $DB_NAME
Database User: $DB_USER
Operating System: $OS

SSH Private Key for GitHub Actions:
$(cat /home/$DEPLOY_USER/.ssh/id_rsa)

SSH Public Key (already added to authorized_keys):
$(cat /home/$DEPLOY_USER/.ssh/id_rsa.pub)

Next Steps:
1. Add the SSH private key to GitHub Secrets as SSH_PRIVATE_KEY
2. Add the server IP ($SERVER_IP) to GitHub Secrets as HOST
3. Add the deploy username ($DEPLOY_USER) to GitHub Secrets as USERNAME
4. Create your .env file and add it to GitHub Secrets as ENV_FILE

Database Connection Details for .env:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASSWORD

Your app will be available at: http://$SERVER_IP
EOF

chown $DEPLOY_USER:$DEPLOY_USER /home/$DEPLOY_USER/deployment-info.txt

echo -e "${GREEN}✅ Server setup completed successfully!${NC}"
echo -e "${YELLOW}📋 Important information saved to: /home/$DEPLOY_USER/deployment-info.txt${NC}"
echo -e "${YELLOW}🔑 SSH private key for GitHub Actions is in that file${NC}"
echo -e "${YELLOW}🌐 Your app will be available at: http://$SERVER_IP${NC}"
echo -e "${YELLOW}📊 Database: PostgreSQL with database '$DB_NAME'${NC}"
echo -e "${YELLOW}🔄 Queue workers will be managed by Supervisor${NC}"
echo -e "${YELLOW}💾 Daily backups configured at 2 AM${NC}"
echo -e "${YELLOW}🖥️  Operating System: $OS${NC}"

echo -e "\n${GREEN}Next steps:${NC}"
echo -e "1. Copy the SSH private key from /home/$DEPLOY_USER/deployment-info.txt"
echo -e "2. Add it to your GitHub repository secrets as SSH_PRIVATE_KEY"
echo -e "3. Add HOST=$SERVER_IP to GitHub secrets"
echo -e "4. Add USERNAME=$DEPLOY_USER to GitHub secrets"
echo -e "5. Create your production .env file and add it as ENV_FILE secret"
echo -e "6. Push to your main branch to trigger deployment!"
