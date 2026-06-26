# Step 1: Clone the Repository
> sudo git clone https://github.com/rimba-portal/starter /srv/starter

# Step 2: Set Correct Folder Ownership
> composer fix-perm
# Step 3: Install Production Dependencies
> cd /srv/starter
> composer install --no-dev --optimize-autoloader

# Step 4: Configure the Environment File (.env)
> cp .env.example .env
> php artisan key:generate

# Step 5: Run Database Migrations
> php artisan migrate --seed

# Step 8: Cache Configuration for Maximum Speed
> php artisan optimize

# Step 9: Validate and Reload Nginx
> sudo nginx -t
> sudo systemctl reload nginx