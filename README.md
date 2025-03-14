# mchaaar-mesdcu-api

API REST développée avec **Symfony 7.2**, utilisant Doctrine ORM pour la gestion des données et Lexik JWT Authentication pour l'authentification sécurisée par tokens JWT.

## 📁 Structure du projet

```
mchaaar-mesdcu-api/
├── assets/ (JS, CSS, Stimulus controllers)
├── bin/ (Scripts CLI)
├── config/ (Configuration Symfony, routes, services)
├── migrations/ (Doctrine migrations)
├── public/ (Point d'entrée public)
├── src/
│   ├── Controller/ (Contrôleurs Symfony)
│   ├── Entity/ (Entités Doctrine)
│   ├── Enum/ (Énumérations du projet)
│   ├── Repository/ (Repositories Doctrine)
│   └── Service/ (Services applicatifs)
```

## 🛠️ Installation

1. Clone le repository :
```bash
git clone https://github.com/ton-utilisateur/mchaaar-mesdcu-api.git
cd mchaaar-mesdcu-api
```

2. Installe les dépendances :
```bash
composer install
```

3. Configure les variables d'environnement (copie et adapte le fichier `.env.dev`) :
```bash
cp .env.dev .env
```

4. Lance les migrations pour configurer la base de données :
```bash
php bin/console doctrine:migrations:migrate
```

5. Lance ton serveur Symfony :
```bash
symfony server:start
```

## 🔑 Technologies utilisées

- Symfony 7.2
- Doctrine ORM
- Lexik JWT Authentication
- Nelbio (swagger)

## 🔗 API et Authentification

L'API est protégée par JWT. Utilise Lexik JWT Authentication pour générer et valider les tokens d'authentification.


## 🤝 Contribution

N'hésite pas à contribuer en créant une branche dédiée (`git checkout -b feature/ma-nouvelle-fonctionnalité`) puis soumets une pull request.

## 📄 Licence

Ce projet est sous licence propriétaire.

