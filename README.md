# 📚 Book Social Network  

## Docker (dev)

Prerequisites: Docker Desktop.

1) Build and start services

```bash
docker compose up --build -d
```

Services:
- App (PHP-FPM): serves PHP at port 9000
- Web (Nginx): http://localhost:8080
- DB (MySQL 8): port 3307 on host (user: laravel / pass: laravel / db: laravel)
- Node (Vite dev): http://localhost:5173

2) First-time setup (inside the app container)

```bash
docker compose exec app php artisan migrate --force
```

If you don't have a `.env`, the container will attempt to copy `docker/.env.docker` to `.env`. If that file doesn't exist, create a local `.env` and set at least:

```
APP_URL=http://localhost:8080
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
VITE_URL=http://localhost:5173
```

3) Stop

```bash
docker compose down
```

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)  
![Blade](https://img.shields.io/badge/Blade-Template-orange?style=for-the-badge)  
![MySQL](https://img.shields.io/badge/MySQL-Database-blue?style=for-the-badge&logo=mysql)  
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)  

Un réseau social innovant de partage et d’échange de livres, développé en **Laravel 12** avec le moteur de template **Blade**.  
Le projet permet aux utilisateurs de créer un compte, partager des livres, interagir avec la communauté et gérer leurs lectures.  

---

## ✨ Fonctionnalités principales

- Authentification & gestion des utilisateurs  
- Rôles : **Admin** et **Utilisateur**  
- Ajout, édition et suppression de livres  
- Interface utilisateur en Blade avec Vite pour les assets  
- Système d’administration avec gestion des utilisateurs  
- Base de données relationnelle avec migrations et seeders  

---

## 🧰 Prérequis

Avant de commencer, assure-toi d’avoir installé :  
- [PHP >= 8.x](https://www.php.net/downloads)  
- [Composer](https://getcomposer.org/download/)  
- [MySQL](https://dev.mysql.com/downloads/)  
- [Node.js](https://nodejs.org/en/download/) + npm  

---

## 🚀 Installation & configuration

1. Clone le dépôt :
   ```bash
   git clone https://github.com/codezella-hub/BOOK_SOCIAL_NETWORK.git
   cd BOOK_SOCIAL_NETWORK
   ```

2. Installe les dépendances PHP :
   ```bash
   composer install
   ```

3. Installe les dépendances JavaScript :
   ```bash
   npm install
   ```




---

## 🗄️ Préparation de la base de données

Exécute les commandes suivantes :  

```bash
php artisan db:create                         # Crée la base de données
php artisan migrate                           # Crée les tables
php artisan db:seed --class=AdminUserSeeder   # Ajoute un admin
```

🔑 Identifiants de l’admin par défaut :  
- **Email** : `admin@socialbook.net`  
- **Mot de passe** : `Admin123!`  

---

## ▶️ Démarrer le projet

- Lance le serveur Laravel :
  ```bash
  php artisan serve
  ```

- Dans un autre terminal, démarre Vite :
  ```bash
  npm run dev
  ```

👉 Par défaut, le projet est accessible sur : [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 📂 Structure du projet

```
BOOK_SOCIAL_NETWORK/
├── app/               # Contrôleurs, modèles, etc.
├── bootstrap/
├── config/
├── database/
│   ├── migrations/    # Migrations
│   └── seeders/       # Seeders (dont AdminUserSeeder)
├── public/            # Fichiers accessibles publiquement
├── resources/
│   ├── views/         # Templates Blade
│   └── js/css         # Assets compilés par Vite
├── routes/            # Routes web et API
├── .env.example
├── composer.json
└── package.json
```

---

## 📸 Captures d’écran (à ajouter)

- Page d’accueil  
- Dashboard Admin  
- Page de gestion des livres  

*(Ajoute tes propres captures ici pour illustrer le projet)*

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :  

1. Fork le projet  
2. Crée une nouvelle branche :  
   ```bash
   git checkout -b feature/ma-fonctionnalite
   ```
3. Effectue tes modifications et commits  
4. Push ta branche :  
   ```bash
   git push origin feature/ma-fonctionnalite
   ```
5. Ouvre une Pull Request  

---

## 📄 Licence

Ce projet est sous licence **MIT**.  
Voir le fichier [LICENSE](LICENSE) pour plus d’informations.  

---

💡 Développé avec ❤️ par **Dev Genuis Team**  
