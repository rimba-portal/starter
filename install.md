# Step 1: Clone the Repository
> sudo git clone https://github.com/rimba-portal/starter /srv/starter
> cd /srv/starter

# Step 2: Set Correct Folder Ownership
> composer fix-perm

# Step 3: Install Production Dependencies
> composer install --no-dev --optimize-autoloader

# Step 4: Configure the Environment File (.env)
> cp .env.example .env
> php artisan key:generate

# Step 5: Run Database Migrations
> php artisan migrate:fresh --seed --quiet --force

# Step 6: Publish assets
> php artisan livewire:publish --assets

# Step 8: Cache Configuration for Maximum Speed
> php artisan optimize

# Step 9: Validate and Reload Nginx
> sudo nginx -t
> sudo systemctl reload nginx


Phase 1: File Setup & Dependency InstallationRun these commands right after cloning your repository into /srv/starter:bash# 1. Set folder ownership so your user can manage files, but nginx can read/write
sudo chown -R $USER:nginx /srv/starter

# 2. Enter directory and install production-optimized dependencies
cd /srv/starter
composer install --no-dev --optimize-autoloader

# 3. Create the environment configuration file
cp .env.example .env
php artisan key:generate
Use code with caution.(Open .env with nano .env and ensure APP_ENV=production, APP_DEBUG=false, and DB_CONNECTION=sqlite are set).Phase 2: Create the SQLite Databasebash# 1. Create the empty SQLite file if it doesn't exist
touch /srv/starter/database/database.sqlite

# 2. Run your database setup migrations
php artisan migrate --force
Use code with caution.Phase 3: Set Strict Permissions & ACLsThis guarantees both nginx and apache (which handles PHP-FPM on Oracle Linux) can write logs, sessions, and database changes without security locks.bash# 1. Set general system-wide permissions (Directories: 755, Files: 644)
find /srv/starter -type d -exec chmod 755 {} \;
find /srv/starter -type f -exec chmod 644 {} \;

# 2. Give group read/write to the specific writeable folders
chmod -R 775 /srv/starter/storage /srv/starter/bootstrap/cache /srv/starter/database
chmod 664 /srv/starter/database/database.sqlite

# 3. Apply bulletproof Access Control Lists (ACLs) for the web servers
sudo setfacl -R -m u:nginx:rwx /srv/starter/storage /srv/starter/bootstrap/cache /srv/starter/database
sudo setfacl -R -d -m u:nginx:rwx /srv/starter/storage /srv/starter/bootstrap/cache /srv/starter/database
sudo setfacl -R -m u:apache:rwx /srv/starter/storage /srv/starter/bootstrap/cache /srv/starter/database
sudo setfacl -R -d -m u:apache:rwx /srv/starter/storage /srv/starter/bootstrap/cache /srv/starter/database

sudo setfacl -m u:nginx:rw /srv/starter/database/database.sqlite
sudo setfacl -m u:apache:rw /srv/starter/database/database.sqlite
Use code with caution.Phase 4: Configure SELinux (Oracle Linux Specific)Tell the operating system's security core to permit Nginx to view the site and write data to the designated directories.bash# 1. Label everything as standard web server read-only files
sudo chcon -Rt httpd_sys_content_t /srv/starter
sudo semanage fcontext -a -t httpd_sys_content_t "/srv/starter(/.*)?"

# 2. Explicitly label storage, cache, and database as Read/Write
sudo chcon -Rt httpd_sys_rw_content_t /srv/starter/storage /srv/starter/bootstrap/cache /srv/starter/database
sudo semanage fcontext -a -t httpd_sys_rw_content_t "/srv/starter/storage(/.*)?"
sudo semanage fcontext -a -t httpd_sys_rw_content_t "/srv/starter/bootstrap/cache(/.*)?"
sudo semanage fcontext -a -t httpd_sys_rw_content_t "/srv/starter/database(/.*)?"
Use code with caution.Phase 5: Build Assets & Cache PerformanceCompile your assets and build the routing cache files so your application runs at peak performance.bash# 1. Compile Livewire's core scripts directly into the public directory to bypass 404 errors
php artisan livewire:publish --assets

# 2. Cache your configuration and routing mappings for production speed
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Test and reload your web server
sudo nginx -t
sudo systemctl reload nginx
Use code with caution.Your Laravel starter kit is now running under a hardened, clean production state!