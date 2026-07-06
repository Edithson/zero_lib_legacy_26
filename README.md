# 📚 ZeroLib

ZeroLib est une plateforme de bibliothèque numérique moderne et performante, conçue pour l'exploration, le téléchargement sécurisé et l'achat de livres. Développée avec le framework **Laravel 13**, **Livewire 3 (avec Volt)** et stylisée avec **Tailwind CSS**, elle offre une expérience utilisateur fluide et un tableau de bord d'administration robuste.

---

## ✨ Fonctionnalités

### 🖥️ Espace Public (Visiteurs & Membres)
- **Catalogue Dynamique :** Recherche par mot-clé, filtrage par catégories et tri (par récence, popularité ou ordre alphabétique).
- **Téléchargements de Livres Gratuits :** 
  - Fichiers stockés sur un disque privé sécurisé (`private`).
  - Validation humaine via **Google reCAPTCHA** pour bloquer les robots.
  - Limitation du débit de téléchargement (rate limiting) pour éviter les abus.
- **Achat de Livres Payants :**
  - Intégration de la passerelle **NotchPay** pour des paiements mobiles (Mobile Money : Orange, MTN, Wave, etc.) et cartes bancaires.
  - Traitement automatisé des statuts de commande via **Webhooks** sécurisés.
- **Authentification Hybride :** Connexion classique par e-mail/mot de passe ou connexion sociale via **Google & Facebook** (Laravel Socialite).
- **Newsletter & Contact :** Inscription à la newsletter et formulaire de contact direct.

### 🛡️ Espace Administration (Admins & Super Admins)
- **Tableau de Bord :** Statistiques en temps réel sur les livres, catégories et téléchargements.
- **Gestion du Contenu (CRUD) :**
  - **Livres :** Gestion des titres, slugs uniques automatiques, auteurs, prix (en FCFA), couvertures d'images et fichiers PDF sécurisés.
  - **Catégories :** Structuration thématique des ouvrages.
- **Supervision :**
  - Consultation des messages de contact reçus.
  - Gestion des abonnés à la newsletter avec exportation au format CSV.
  - Historique détaillé et filtrable des téléchargements.

### 👑 Espace Super Administrateur (Type 3 uniquement)
- **Configuration Globale :** Gestion des métadonnées du site (Nom du site, e-mail de contact, e-mail de l'administrateur principal, téléphone, réseaux sociaux : GitHub, LinkedIn).
- **Gestion des Utilisateurs :** CRUD complet des utilisateurs et attribution des rôles.

---

## 🛠️ Stack Technique

- **Backend :** PHP 8.3+, Laravel 13
- **Frontend :** Livewire 3 (Volt), Blade, Tailwind CSS, Vite.js
- **Base de données :** MySQL (ou SQLite pour les tests)
- **Paiements :** NotchPay API
- **Sécurité :** Google reCAPTCHA (v2/v3)
- **Intégrations :** Laravel Socialite (OAuth Google/Facebook), Sentry (Suivi des erreurs en production)

---

## 🏗️ Structure des Rôles & Sécurité

L'application utilise un système de rôles basé sur la table `types` liée aux utilisateurs (`users.type_id`) :

| Type ID | Rôle | Description | Accès autorisés |
| :---: | :--- | :--- | :--- |
| **1** | `user` | Utilisateur standard | Espace public, achats, téléchargements. |
| **2** | `admin` | Administrateur | Dashboard, CRUD Livres/Catégories, Logs, Contacts. |
| **3** | `super admin` | Super Administrateur | Tous les accès + Gestion des utilisateurs & Paramètres site. |

---

## ⚙️ Installation & Configuration

### 📋 Prérequis
- PHP `>= 8.3`
- Composer
- Node.js & NPM
- Un serveur de base de données (MySQL, PostgreSQL, ou SQLite)

### 🚀 Étapes d'installation

1. **Cloner le projet :**
   ```bash
   git clone https://github.com/votre-compte/zero-lib.git
   cd zero-lib
   ```

2. **Lancer le script de configuration automatique :**
   Le projet intègre un script Composer pour simplifier le déploiement local :
   ```bash
   composer run setup
   ```
   *Ce script exécute successivement : l'installation des dépendances PHP et Node, la copie du `.env.example` vers `.env` (si non existant), la génération de la clé d'application, la migration des tables et la compilation des assets de production.*

3. **Configurer les variables d'environnement (`.env`) :**
   Ouvrez le fichier `.env` généré et configurez les éléments clés suivants :
   
   - **Base de données :**
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nom_de_votre_base
     DB_USERNAME=votre_utilisateur
     DB_PASSWORD=votre_mot_de_passe
     ```
   
   - **NotchPay API (Achat de livres) :**
     ```env
     NOTCHPAY_PUBLIC_KEY=votre_cle_publique
     ```
   
   - **Google reCAPTCHA (Téléchargement sécurisé) :**
     ```env
     RECAPTCHA_SITE_KEY=votre_cle_site
     RECAPTCHA_SECRET_KEY=votre_cle_secrete
     ```

   - **OAuth Socialite (Connexion Google / Facebook) :**
     ```env
     GOOGLE_CLIENT_ID=votre_client_id
     GOOGLE_CLIENT_SECRET=votre_client_secret
     GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
     
     FACEBOOK_CLIENT_ID=votre_client_id
     FACEBOOK_CLIENT_SECRET=votre_client_secret
     FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"
     ```

4. **Peupler la base de données (Seeding) :**
   Pour insérer les types de rôles et créer un compte administrateur par défaut :
   ```bash
   php artisan db:seed
   ```

---

## 🔑 Identifiants de test par défaut

Après avoir exécuté le *Seeder*, vous pouvez vous connecter avec le compte Super Administrateur suivant :
- **E-mail :** `test@example.com`
- **Mot de passe :** `74l4m4150n`

---

## 💻 Utilisation en Développement

Pour lancer tous les serveurs nécessaires en local simultanément (Serveur PHP Laravel, Vite.js, Queue Worker de tâches en arrière-plan et Pail pour les logs) :

```bash
composer run dev
```

Pour lancer les tests unitaires et fonctionnels :
```bash
composer run test
```

---

## 📂 Organisation du Disque de Stockage

Pour stocker en toute sécurité les livres achetables ou gratuits, le projet utilise deux disques Laravel :
- Le disque `public` (pour les images de couverture de livres et le logo du site).
- Le disque `private` (pour les fichiers PDF de livres, empêchant l'accès direct par URL). Assurez-vous que votre configuration de stockage local ou S3 est correctement liée.
