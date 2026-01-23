# PayFusionPOC

Ce projet est un proof of concept (POC) Symfony intégrant les paiements Stripe et PayPal via le design pattern **Strategy**.

## Fonctionnalités

- Intégration de Stripe pour les paiements en ligne
- Intégration de PayPal pour les paiements en ligne
- Exemple de configuration et de gestion des clés API

## Prérequis

- PHP >= 8.1
- Composer
- Symfony CLI
- MySQL

## Installation

```bash
composer install
cp .env .env.local
# Configure tes clés Stripe et PayPal dans .env.local
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start
```

## Utilisation

Accède à l’application sur [http://localhost:8000](http://localhost:8000).
