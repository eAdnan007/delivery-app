#!/bin/sh
echo "\n\n\n\n==========\nInstalling docker:\n==========\n"
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -a -G docker $USER
echo "==========\nDocker installed\n==========\n"


echo "\n\n\n\n==========\nInstalling docker compose\n==========\n"
sudo curl -L --fail https://github.com/docker/compose/releases/download/1.22.0/run.sh -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
echo "==========\nFinished installing docker-compose\n==========\n"


echo "\n\n\n\n==========\nSetting up project\n==========\n"
sudo docker-compose up -d
sudo docker run --rm -v $(pwd):/app composer install
cp .env.example .env
sudo docker-compose exec app php artisan key:generate
sudo docker-compose exec app php artisan migrate --force
echo "==========\nProject is up\n==========\n"


echo "\n\n\n\n==========\nRun tests\n==========\n"
sudo docker-compose exec app vendor/bin/phpuni
echo "==========\nUnit test complete\n==========\n"