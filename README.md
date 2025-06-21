# ✈️ Planificateur de Voyages Insolites

Une application Symfony moderne pour planifier et découvrir des voyages extraordinaires avec un système de recommandations personnalisées.

## 🎯 Fonctionnalités

### 🔐 Authentification et Gestion des Utilisateurs
- **Inscription/Connexion** avec validation des données
- **Rôles utilisateurs** : ROLE_USER, ROLE_ADMIN
- **Profil utilisateur** avec informations personnelles
- **Sécurité** avec hachage des mots de passe et protection CSRF

### 🌍 Gestion des Destinations
- **Destinations publiques** accessibles à tous les visiteurs
- **Destinations privées** pour les administrateurs
- **Informations détaillées** : climat, budget, durée, type de voyage
- **Images** pour chaque destination
- **Système de catégorisation** par type (aventure, culture, détente, etc.)

### ❤️ Système de Favoris
- **Ajout/Retrait** de destinations aux favoris
- **Notes personnelles** sur chaque favori
- **Statut public/privé** pour partager ou garder privé
- **Page communautaire** pour découvrir les favoris publics
- **Compteur de popularité** des destinations

### 📅 Planification de Voyages
- **Création de voyages** avec dates de départ et retour
- **Budget estimé** et notes personnelles
- **Statuts de voyage** : planifié, en cours, terminé, annulé
- **Informations détaillées** : accompagnants, hébergement, transport
- **Durée de séjour** calculée automatiquement

### 🔍 Recherche Avancée
- **Recherche par nom** de destination
- **Filtres multiples** : pays, ville, type, budget, climat
- **Recherche par préférences** utilisateur
- **Interface intuitive** avec formulaires dynamiques

### ⚙️ Système de Préférences
- **Préférences personnalisées** par utilisateur
- **Types de voyage** préférés
- **Niveaux de budget** souhaités
- **Climats** préférés
- **Recommandations** basées sur les préférences

### 👑 Dashboard Administrateur
- **Interface moderne** avec sidebar et graphiques
- **Statistiques en temps réel** :
  - Nombre total de destinations, utilisateurs, favoris, planifications
  - Graphiques des types de destinations et budgets
  - Destinations populaires et récentes
- **Gestion complète** :
  - Destinations avec statistiques détaillées
  - Utilisateurs avec badges de statut
  - Favoris avec notes et statuts
  - Planifications avec dates et budgets
- **Design responsive** avec animations et transitions

## 🛠️ Technologies Utilisées

### Backend
- **Symfony 7.0** - Framework PHP moderne
- **Doctrine ORM** - Gestion de la base de données
- **Security Bundle** - Authentification et autorisation
- **Form Bundle** - Gestion des formulaires
- **Validator** - Validation des données
- **Twig** - Moteur de templates

### Frontend
- **Bootstrap 5.3** - Framework CSS responsive
- **Font Awesome** - Icônes
- **Chart.js** - Graphiques interactifs
- **JavaScript vanilla** - Animations et interactions

### Base de Données
- **MySQL/PostgreSQL** - Base de données relationnelle
- **Doctrine Migrations** - Gestion des versions de schéma

## 📁 Structure du Projet

```
cours_dl/
├── assets/                 # Assets frontend (JS, CSS)
├── bin/                   # Scripts Symfony
├── config/                # Configuration Symfony
├── migrations/            # Migrations de base de données
├── public/               # Fichiers publics
├── src/
│   ├── Command/          # Commandes console
│   ├── Controller/       # Contrôleurs
│   ├── Entity/          # Entités Doctrine
│   ├── Form/            # Formulaires
│   ├── Repository/      # Repositories
│   └── Kernel.php
├── templates/            # Templates Twig
│   ├── admin/           # Templates du dashboard admin
│   ├── destination/     # Templates des destinations
│   ├── favori/          # Templates des favoris
│   ├── home/            # Templates d'accueil
│   ├── planification/   # Templates des planifications
│   ├── preference/      # Templates des préférences
│   ├── public_destination/ # Templates publics
│   ├── registration/    # Templates d'inscription
│   ├── search/          # Templates de recherche
│   └── security/        # Templates de sécurité
├── tests/               # Tests unitaires
├── translations/        # Fichiers de traduction
├── var/                 # Fichiers temporaires
└── vendor/              # Dépendances Composer
```

## 🚀 Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- Symfony CLI (optionnel)
- Base de données MySQL ou PostgreSQL

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone [url-du-repo]
cd cours_dl
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**
```bash
# Créer le fichier .env.local avec vos paramètres de base de données
cp .env .env.local
# Éditer .env.local avec vos paramètres
```

4. **Créer la base de données et exécuter les migrations**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Créer un utilisateur administrateur**
```bash
php bin/console app:create-admin
```

6. **Charger des données d'exemple (optionnel)**
```bash
php bin/console app:create-sample-destinations
php bin/console app:create-sample-favoris
```

7. **Démarrer le serveur**
```bash
# Avec Symfony CLI
symfony server:start

# Ou avec le serveur PHP intégré
php -S localhost:8000 -t public
```

## 📊 Entités Principales

### User
- Informations personnelles (nom, prénom, email)
- Rôles et permissions
- Relations avec favoris, planifications, préférences
- Date de création

### Destination
- Informations géographiques (nom, ville, pays)
- Caractéristiques (climat, type, budget, durée)
- Image et description
- Dates de création/modification

### Favori
- Relation utilisateur-destination
- Notes personnelles
- Statut public/privé
- Date d'ajout

### Planification
- Dates de départ et retour
- Budget estimé
- Notes et accompagnants
- Statut du voyage
- Informations détaillées (hébergement, transport)

### Preference
- Préférences utilisateur
- Types de voyage préférés
- Niveaux de budget
- Climats préférés

## 🎨 Interface Utilisateur

### Page d'Accueil
- **Hero section** avec call-to-action
- **Statistiques générales** de l'application
- **Destinations populaires** avec compteurs de favoris
- **Favoris communautaires** récents
- **Statistiques utilisateur** (si connecté)

### Navigation
- **Menu responsive** avec Bootstrap
- **Accès rapide** aux fonctionnalités principales
- **Menu déroulant** utilisateur avec options
- **Indicateurs visuels** pour les administrateurs

### Dashboard Admin
- **Sidebar moderne** avec gradient
- **Statistiques en temps réel**
- **Graphiques interactifs** avec Chart.js
- **Gestion complète** de toutes les entités
- **Modals** pour les détails
- **Design responsive** avec animations

## 🔧 Commandes Console

### Commandes personnalisées
```bash
# Créer un administrateur
php bin/console app:create-admin

# Créer des destinations d'exemple
php bin/console app:create-sample-destinations

# Créer des favoris d'exemple
php bin/console app:create-sample-favoris
```

### Commandes Symfony standard
```bash
# Gestion de la base de données
php bin/console doctrine:migrations:migrate
php bin/console doctrine:schema:update --force

# Cache
php bin/console cache:clear
php bin/console cache:warmup

# Debug
php bin/console debug:router
php bin/console debug:container
```

## 🛡️ Sécurité

- **Authentification** avec Symfony Security
- **Hachage des mots de passe** avec bcrypt
- **Protection CSRF** sur tous les formulaires
- **Validation des données** avec les contraintes Symfony
- **Gestion des rôles** et permissions
- **Sécurisation des routes** avec annotations

## 📱 Responsive Design

- **Mobile-first** avec Bootstrap 5
- **Grilles adaptatives** pour tous les écrans
- **Navigation mobile** avec menu hamburger
- **Cartes flexibles** qui s'adaptent
- **Modals** optimisées pour mobile

## 🎯 Fonctionnalités Avancées

### Système de Recommandations
- Basé sur les préférences utilisateur
- Analyse des favoris communautaires
- Suggestions personnalisées

### Statistiques et Analytics
- Compteurs de popularité
- Graphiques interactifs
- Statistiques en temps réel
- Métriques utilisateur

### Interface Moderne
- Animations CSS et JavaScript
- Transitions fluides
- Design Material avec ombres
- Palette de couleurs cohérente

## 🚀 Déploiement

### Environnement de Production
1. Configurer les variables d'environnement
2. Optimiser le cache : `php bin/console cache:clear --env=prod`
3. Configurer la base de données de production
4. Exécuter les migrations
5. Configurer le serveur web (Apache/Nginx)

### Variables d'Environnement
```env
# Base de données
DATABASE_URL="mysql://user:password@localhost/db_name"

# Sécurité
APP_SECRET="your-secret-key"

# Environnement
APP_ENV=prod
APP_DEBUG=false
```

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👨‍💻 Auteur

Développé dans le cadre d'un cours Symfony avec toutes les bonnes pratiques modernes.

## 🙏 Remerciements

- Symfony Team pour le framework
- Bootstrap Team pour le CSS framework
- Chart.js pour les graphiques
- Font Awesome pour les icônes

---

**✨ Un projet complet et moderne de planification de voyages insolites !** 
