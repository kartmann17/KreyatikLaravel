# Kréyatik Studio - Application de Gestion Interne

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

## 📋 Description

Application de gestion interne développée pour Kréyatik Studio. Cette plateforme permet la gestion de projets, clients, tickets et ressources avec des espaces dédiés pour les administrateurs et les clients.



## ✨ Fonctionnalités

- **Tableau de bord** - Vue d'ensemble personnalisée selon le rôle
- **Gestion des clients** - Ajout, modification et suivi complet des clients
- **Gestion des projets** - Création et suivi des projets avec jalons
- **Système de tickets** - Support client avec historique et priorisation
- **Profil utilisateur** - Personnalisation des paramètres et préférences

## 🔧 Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Node.js et NPM
- Serveur web (Apache, Nginx, etc.)

## 🚀 Installation

### Cloner le dépôt

```bash
git clone https://github.com/LaurentP-56/lionel.git
cd lionel-app
```

### Installer les dépendances

```bash
composer install
npm install
```

### Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Éditez le fichier `.env` pour configurer votre base de données :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kreyatik_db
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

### Migrations et données initiales

```bash
php artisan migrate --seed
```

### Compilation des assets

```bash
npm run dev
# Ou en production
npm run build
```

### Configuration des permissions (Unix/Linux)

```bash
chmod -R 775 storage bootstrap/cache
```

## 🐳 Installation avec Docker

### Prérequis
- Docker
- Docker Compose

### Installation rapide

1. Cloner le dépôt
```bash
git clone https://github.com/LaurentP-56/lionel.git
cd lionel-app
```

2. Créer les répertoires nécessaires pour Docker
```bash
mkdir -p docker/nginx/conf.d
```

3. Configurer l'environnement
```bash
cp .env.example .env
```

4. Modifier votre fichier .env pour Docker
```
DB_HOST=db
DB_DATABASE=kreyatik_db
DB_USERNAME=kreyatik
DB_PASSWORD=password
REDIS_HOST=redis
MAIL_HOST=mailhog
```

5. Lancer les conteneurs Docker
```bash
docker-compose up -d
```

4. Installer les dépendances et configurer l'application
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm install
docker-compose exec app npm run build
```

5. Accéder à l'application
- **Administration** : http://localhost/admin
- **Espace client** : http://localhost/client

### Services disponibles

| Service | Description | Port |
|---------|-------------|------|
| app | Application Laravel | 80 |
| db | Base de données MySQL | 3306 |
| redis | Cache Redis | 6379 |
| mailhog | Serveur de mails pour test | 8025 |

### Commandes utiles

```bash
# Arrêter les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f

# Exécuter des commandes dans le conteneur
docker-compose exec app php artisan cache:clear
```

## 🖥️ Utilisation

### Lancer l'application en mode développement

```bash
php artisan serve
```

Accédez à l'application via :
- **Administration** : http://localhost:8000/admin
- **Espace client** : http://localhost:8000/client

### Utilisateurs par défaut

| Type | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@example.com | password |
| Client | client@example.com | password |

## 👥 Structure des rôles

- **Administrateur** - Accès total à l'application
- **Staff** - Gestion sans droits administratifs
- **Client** - Accès limité à leurs projets et tickets

## 🔍 Tests

```bash
php artisan test
```

## 🛠️ Résolution des problèmes

| Problème | Solution |
|----------|----------|
| Erreur de droits | Vérifier permissions sur `storage` et `bootstrap/cache` |
| Pages blanches | Consulter les logs dans `storage/logs/laravel.log` |
| Erreurs de base de données | Vérifier configuration dans `.env` |

## 📝 Contribuer

1. Forkez le projet
2. Créez votre branche (`git checkout -b feature/amazing-feature`)
3. Committez vos changements (`git commit -m 'feat: ajout d'une fonctionnalité'`)
4. Poussez vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrez une Pull Request

## 📄 License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🔐 Sécurité

Si vous découvrez une faille de sécurité, veuillez envoyer un email à [laurentbwa@gmail.com](mailto:laurentbwa@gmail.com).

## 📞 Contact

Pour toute question ou assistance technique, contactez l'équipe de développement de Kréyatik Studio.
