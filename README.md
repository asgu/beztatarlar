# GIT Submodules
git submodule add --force -b prod ../../internal/roles.git playbooks/roles
# GIT Submodules update
git submodule update --recursive --remote
### or
git pull --recurse-submodules

# vagrant-config.yml. Update on deploy

###### machine_name
###### simple_domain
###### project_db_name
###### ip

# app/.env.example
###### DB accesses
###### DB_CONNECTION=mysql
###### DB_HOST=127.0.0.1
###### DB_PORT=3306
###### DB_DATABASE=<db in vagrant>
###### DB_USERNAME=user
###### DB_PASSWORD=user
###### APP_NAME="<project title>"
###### APP_URL=<url>

###### POSTMARK_TOKEN=<postmark token>
###### POSTMARK_FROM_EMAIL=<postmark mail to be shown>
###### FRONTEND_URL=<frontend url for mails>

# Admin user crate
php artisan db:seed --class=Modules\\Admin\\Database\\Seeders\\CreateAdminSeeder
###### Default pass is 123456

php artisan admin:create email@mail.ru password

# Multilang
php artisan db:seed --class=Modules\\Language\\Database\\Seeders\\AppLanguagesSeeder
php artisan db:seed --class=Modules\\Language\\Database\\Seeders\\AppLanguageMessagesSeeder


# !!!Execute on first deploy!!!
php artisan db:seed --class=Modules\\Admin\\Database\\Seeders\\CreateAdminSeeder
php artisan db:seed --class=Modules\\Language\\Database\\Seeders\\AppLanguagesSeeder
php artisan db:seed --class=Modules\\Language\\Database\\Seeders\\AppLanguageMessagesSeeder
php artisan db:seed --class=Modules\\Language\\Database\\Seeders\\BackRuSeeder
