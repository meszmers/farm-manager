## Setup project

``git clone https://github.com/meszmers/farm-manager.git``

``composer install``

``cp .env.example .env``

``./vendor/bin/sail up``

``./vendor/bin/sail php artisan key:generate``

``./vendor/bin/sail php artisan migrate:fresh --seed``

``npm run dev``
