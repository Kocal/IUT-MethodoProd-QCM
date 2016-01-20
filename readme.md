# [Méthodo. de prod.] Projet QCM

TP en Méthodologie de Production ayant pour but de créer un système de gestion de QCM.
La création de QCM se fait par les professeurs, et les étudiants peuvent ensuite y participer.

## Installation
```bash
$ git clone https://github.com/Kocal/IUT-MethodoProd-QCM.git
$ cp .env.example .env
$ npm install
$ composer install
$ php artisan key:generate
$ chmod -R 777 storage
```

Note : L'utilisation de [Homestead](https://laravel.com/docs/5.2/homestead) est conseillée.
