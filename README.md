# La Cosmetica API 🌿💄

API REST développée avec **Laravel** pour la gestion des ventes de cosmétiques bio d'une pharmacie naturelle.

Elle permet aux clients de :

- s'authentifier de manière sécurisée,
- consulter les produits disponibles et leurs détails,
- passer, suivre et annuler une commande.

Elle permet aux employés de :

- gérer le statut des commandes.

Elle permet aussi aux administrateurs de :

- gérer les produits et les catégories,
- consulter les statistiques de ventes.

---

## 📌 Contexte du projet

L'objectif principal de **La Cosmetica API** est de digitaliser les ventes d'une pharmacie naturelle en pleine croissance et d'améliorer l'expérience client.

Chaque produit est caractérisé par :

- un **nom** et une **description**,
- un **prix**,
- une ou plusieurs **images** (4 maximum),
- une **catégorie** (crèmes, huiles, sérums…),
- un **slug** lisible pour l'accès via URL.

Le système doit permettre une gestion précise des commandes et des stocks, tout en offrant une API claire et documentée pour une future intégration avec des applications web ou mobiles.

---

## 🎯 Objectifs

- Développer une API REST avec **Laravel**
- Utiliser **JWT** pour l'authentification
- Gérer les produits, les catégories et les commandes
- Implémenter des rôles utilisateurs (client, employé, administrateur)
- Utiliser des **slugs** lisibles avec **Spatie Laravel Sluggable**
- Fournir une documentation API claire
- Tester l'ensemble des fonctionnalités avec **Unit Tests** et **Postman**

---

## 🧩 Fonctionnalités principales

### 🔐 Client

- Inscription et connexion avec **JWT**
- Consultation de la liste des produits disponibles (nom, description, prix, images, catégorie)
- Accès aux détails d'un produit via son slug (ex. : `/api/products/creme-hydratante-bio`)
- Passation d'une commande en sélectionnant des produits par leurs slugs et quantités
- Vérification de l'état d'une commande (en attente, en préparation, livrée)
- Annulation d'une commande avant qu'elle ne soit préparée

### 🪪 Employé

- Connexion avec des permissions adaptées à son rôle
- Mise à jour du statut d'une commande (préparée et prête à être livrée)

### 🔑 Administrateur

- Créer, modifier ou supprimer des catégories
- Créer, modifier ou supprimer des produits
- Définir et mettre à jour les images d'un produit (4 images maximum)
- Consulter les statistiques :
  - ventes globales
  - produits les plus populaires
  - répartition par catégorie

### 🧪 Développeur

- Tests unitaires pour chaque fonctionnalité
- Tests Postman pour différents scénarios
- Documentation détaillée des endpoints
- Gestion des exceptions avec messages clairs et codes HTTP adaptés
- Implémentation d'un **DAO** pour les interactions avec la base de données

---

## ⭐ Bonus

- Utilisation de **DTO** pour structurer et valider les données échangées
- Limitation à **4 images par produit** avec message d'erreur explicite
- Conteneurisation avec **Docker**
- Notification par **email ou SMS** à la livraison d'une commande

---

## 🛠️ Stack technique

| Technologie | Rôle |
|---|---|
| **PHP / Laravel** | Framework principal |
| **JWT (tymon/jwt-auth)** | Authentification par token |
| **MySQL** | Base de données |
| **Spatie Laravel Sluggable** | Génération de slugs lisibles |
| **Laravel Query Builder** | Statistiques et requêtes avancées |
| **Postman** | Tests API |
| **Swagger / Scribe** | Documentation |
| **Docker** *(bonus)* | Conteneurisation |

---

## 🗂️ Structure fonctionnelle

Le projet est organisé autour des modules suivants :

- **Auth**
- **Users / Roles**
- **Categories**
- **Products**
- **Orders**
- **Admin Dashboard / Statistics**
- **Notifications**

---

## 🧱 Modèle métier

### Entités principales

| Entité | Description |
|---|---|
| `User` | Utilisateur authentifié (client, employé, admin) |
| `Role` | Rôle attribué à un utilisateur |
| `Category` | Catégorie de produits cosmétiques |
| `Product` | Produit cosmétique bio |
| `ProductImage` | Image associée à un produit (max 4) |
| `Order` | Commande passée par un client |
| `OrderItem` | Ligne de commande (produit + quantité) |
| `Notification` | Alerte envoyée à l'utilisateur |

### Relations

- Un `User` peut avoir plusieurs `Orders`
- Une `Category` peut contenir plusieurs `Products`
- Un `Product` peut avoir jusqu'à 4 `ProductImages`
- Une `Order` est composée de plusieurs `OrderItems`
- Un `User` peut recevoir plusieurs `Notifications`

---

## 🔑 Authentification

L'authentification se fait via **JWT**. Le token doit être envoyé dans chaque requête protégée :
```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

---

## 📡 Endpoints principaux

> Les routes exactes peuvent varier selon votre implémentation.

### Auth

| Méthode | Route | Description |
|---|---|---|
| `POST` | `/api/register` | Inscription |
| `POST` | `/api/login` | Connexion |
| `POST` | `/api/logout` | Déconnexion |
| `GET` | `/api/user` | Utilisateur authentifié |

### Categories

| Méthode | Route | Description |
|---|---|---|
| `GET` | `/api/categories` | Liste des catégories |
| `GET` | `/api/categories/{id}` | Détail d'une catégorie |
| `POST` | `/api/categories` | Créer une catégorie *(admin)* |
| `PUT` | `/api/categories/{id}` | Modifier une catégorie *(admin)* |
| `DELETE` | `/api/categories/{id}` | Supprimer une catégorie *(admin)* |

### Products

| Méthode | Route | Description |
|---|---|---|
| `GET` | `/api/products` | Liste des produits disponibles |
| `GET` | `/api/products/{slug}` | Détail d'un produit via son slug |
| `POST` | `/api/products` | Créer un produit *(admin)* |
| `PUT` | `/api/products/{id}` | Modifier un produit *(admin)* |
| `DELETE` | `/api/products/{id}` | Supprimer un produit *(admin)* |

### Orders

| Méthode | Route | Description |
|---|---|---|
| `GET` | `/api/orders` | Liste des commandes du client |
| `POST` | `/api/orders` | Passer une commande |
| `GET` | `/api/orders/{id}` | Détail d'une commande |
| `PATCH` | `/api/orders/{id}/cancel` | Annuler une commande *(client)* |
| `PATCH` | `/api/orders/{id}/prepare` | Marquer comme préparée *(employé)* |

### Admin / Statistics

| Méthode | Route | Description |
|---|---|---|
| `GET` | `/api/admin/statistics` | Statistiques globales des ventes |

---

## 📘 Exemples de payload

### Passer une commande — Requête
```json
{
  "items": [
    { "slug": "creme-hydratante-bio", "quantity": 2 },
    { "slug": "huile-argan-pure", "quantity": 1 }
  ]
}
```

### Passer une commande — Réponse `201 Created`
```json
{
  "message": "Commande créée avec succès",
  "data": {
    "id": 7,
    "status": "pending",
    "items": [
      { "product": "Crème Hydratante Bio", "quantity": 2, "unit_price": 12.99 },
      { "product": "Huile Argan Pure", "quantity": 1, "unit_price": 18.50 }
    ],
    "total": 44.48,
    "created_at": "2026-03-16T10:00:00Z"
  }
}
```

---

## ✅ Règles métier

- Un client ne peut annuler une commande que si son statut est **en attente**
- Seul le **propriétaire** d'une commande peut la consulter ou l'annuler
- Seul l'**employé** ou l'**administrateur** peut mettre à jour le statut d'une commande
- Seul l'**administrateur** peut gérer les produits et les catégories
- Un produit ne peut pas avoir plus de **4 images** — un message d'erreur clair est retourné si la limite est dépassée
- Les slugs des produits sont générés automatiquement via **Spatie Laravel Sluggable**

---

## ⚠️ Codes de réponse HTTP

| Code | Statut | Signification |
|---|---|---|
| `200` | OK | Succès |
| `201` | Created | Ressource créée |
| `204` | No Content | Suppression réussie |
| `401` | Unauthorized | Utilisateur non authentifié |
| `403` | Forbidden | Accès refusé |
| `404` | Not Found | Ressource introuvable |
| `409` | Conflict | Conflit métier (ex. commande non annulable) |
| `422` | Unprocessable Entity | Erreur de validation |

---

## 🧪 Tests

### Tests unitaires

Des tests doivent couvrir :

- Authentification
- Gestion des catégories
- Récupération des produits par slug
- Passation et annulation de commandes
- Statistiques admin

```bash
php artisan test
```

### Tests Postman

La collection Postman doit valider :

- Les cas nominaux
- Les cas d'erreur
- Les permissions client / employé / admin
- Les règles de validation

---

## 📄 Documentation API

Générée avec **Postman**, **Swagger / OpenAPI** ou **Scribe**, elle doit inclure :

- La description de chaque endpoint
- Les paramètres attendus
- Les exemples de requêtes et réponses
- Les erreurs possibles

---

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone https://github.com/fadiinsaf/La-Cosmetica.git
cd la-cosmetica
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurer `.env`
```env
APP_NAME=LaCosmeticaAPI
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=la_cosmetica
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=
```

### 5. Générer la clé JWT
```bash
php artisan jwt:secret
```

### 6. Base de données
```bash
php artisan migrate
php artisan db:seed
```

### 7. Démarrer
```bash
php artisan serve
```

---

## 🐳 Docker *(bonus)*
```bash
docker-compose up -d
docker-compose exec app php artisan migrate
```

---

## 📬 Notifications *(bonus)*

Envoi automatique :

- ✉️ **Email** ou 📱 **SMS**
- Lors de la **livraison** de la commande

---

## 👨‍💻 Auteur

**Fadi Insaf** – [GitHub](https://github.com/fadiinsaf) | [Email](mailto:fadiinafff@gmail.com)

---

## 📜 Licence

Ce projet est destiné à un usage pédagogique et académique.
