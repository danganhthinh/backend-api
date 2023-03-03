# Bridge
Pre-requisites

    PHP >= 8.1
    Git
    Composer
    NodeJS & Npm
    Pm2 
    Redis (sudo apt install redis-server - ubuntu)
    Crontab
    MySQL (sudo apt install mysql-server - ubuntu)

### Installation
    * Install PHP version
        sudo apt-get update
        sudo apt -y install software-properties-common
        sudo add-apt-repository ppa:ondrej/php
        sudo apt-get update
        sudo apt-get install -y php8.1 php8.1-cli php8.1-json php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-gmp

    * Install mysql
        sudo apt install mysql-server
        https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-20-04
    
    * Edit php.ini
        upload_max_filesize = 200M
        post_max_size = 200M

    * Copy .env file from .env.example and then change database configuration
        $ cp .env.example .env
        $ cp laravel-echo-server.json.example laravel-echo-server.json.
    
    * Install composer modules. You'll need to run this command again if there's any update in the file composer.json
        $ composer update
        $ npm install
    
    * Database Seeder dumpload
        $ composer dump-autoload

    * Init database or fresh DB
        $ php artisan migrate:fresh --seed

    * Install Pm2
        $ sudo npm i -g pm2
        $ pm2 start laravel-queue-worker.yml

    * Install Crontab
        $ sudo apt install cron
        $ sudo systemctl enable cron
        $ crontab -e
        This will open the server Crontab file, paste the code below into the file, save and then exit: * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1


### Architecture
    - Web
