# Gestion des employés et des congés (RH)

Application web permettant à une entreprise de gérer ses employés, ses départements et de traiter les demandes de congé de ses employés.

## Contexte

Une entreprise souhaite gérer ses employés (nom, poste, département, email) et traiter leurs demandes de congé (type, date de début, date de fin, statut). L'application est développée en PHP orienté objet, avec un accès sécurisé à la base de données via PDO.

## Fonctionnalités

- [x] Connexion à la base de données via une classe `Database` (PDO)
- [ ] Gestion des employés (ajouter, lister, modifier, supprimer)
- [x] Gestion des départements (ajouter, lister, modifier, supprimer)
- [ ] Soumission d'une demande de congé
- [ ] Validation ou refus d'un congé
- [ ] Liste des congés par employé ou par statut
- [ ] Recherche

## Modèle de données

- **Employe** : nom, poste, département, email
- **Departement** : nom
- **Conge** : employé, type, date début, date fin, statut

Relations : un département a plusieurs employés, un employé a plusieurs congés.

## Structure du projet

```
config/Database.php          -> classe de connexion PDO
classes/                     -> classes entités (Employe, Departement, Conge) et gestionnaires (Managers)
views/                       -> pages d'affichage (formulaires, listes)
public/index.php             -> point d'entrée de l'application
public/assets/css/style.css  -> style personnalisé (servi par le serveur web)
sql/script.sql                -> script de création de la base de données
```

## Prérequis

- PHP 8+
- MySQL / MariaDB
- Un serveur local (XAMPP, WAMP, ou serveur intégré PHP)

## Installation

1. Cloner le dépôt :
   ```
   git clone https://github.com/abdoulazizgueye0805/Gueye-Abdoul-Aziz-Gestion-des-employes-et-des-conges-RH-.git
   ```
2. Importer le script SQL pour créer la base de données :
   ```
   mysql -u root -p < sql/script.sql
   ```
3. Adapter les identifiants de connexion dans `config/Database.php` si nécessaire (host, utilisateur, mot de passe).
4. Lancer un serveur local à la racine du projet, par exemple :
   ```
   php -S localhost:8000 -t public
   ```
5. Ouvrir `http://localhost:8000` dans le navigateur.

## Méthodologie

- Programmation orientée objet : une classe entité par objet métier (`Employe`, `Departement`, `Conge`) avec attributs privés et getters/setters (encapsulation).
- Une classe gestionnaire (Manager) par entité, contenant les méthodes CRUD (`ajouter`, `lister`, `trouverParId`, `modifier`, `supprimer`).
- Toutes les requêtes SQL sont préparées (protection contre les injections SQL).
- Développement progressif avec un commit par étape fonctionnelle.

## Sources

- Documentation officielle PHP : https://www.php.net/manual/fr/book.pdo.php
- Documentation MySQL : https://dev.mysql.com/doc/

## Auteur

Abdoul Aziz Gueye
