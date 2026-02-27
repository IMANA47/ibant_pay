# Projet : IBANT Pay - Gestion des Étudiants et Paiements

## Description
IBANT Pay est une application web de gestion au sein d'un établissement scolaire. Elle permet de gérer les classes, les étudiants (incluant la gestion des photos de profil) ainsi que le suivi des paiements de frais académiques.

---

## Contenu du projet
- **`public/`** : Contient les points d'entrée de l'application (Vues et contrôleurs frontaux pour les classes, étudiants et paiements).
- **`src/`** : Logique métier (Services) et accès aux données (Repositories).
- **`config/`** : Configuration centrale, incluant la connexion PDO à la base de données (`databas.php`).
- **`sql/`** : Fichier `ibant_pay.sql` contenant le schéma de base de données à jour.
- **`index.php`** : Point d'entrée à la racine redirigeant vers l'interface principale.

---

## Fonctionnalités principales
- **Gestion des Classes** : Création, modification, suppression. Protection contre les identifiants en doublon.
- **Gestion des Étudiants** : Ajout avec matricule unique, informations personnelles, upload et affichage de la photo, liaison à une classe.
- **Gestion des Paiements** : Enregistrement de paiements liés à un étudiant avec génération de numéros de reçus uniques et suivi des montants par année académique.
- **Sécurité et robustesse** : Requêtes préparées (PDO) contre les injections SQL, gestion propre des erreurs de contraintes de base de données (doublons, clés étrangères).
- **Interface UI/UX** : Design responsive avec Bootstrap 5, alertes dynamiques (Flash messages).

---

## Installation et configuration

1. **Prérequis** : Serveur web local (XAMPP, WAMP, etc.) avec PHP 8+ et MySQL.
2. **Déploiement** : Copier le dossier du projet sous le nom `ibant_pay` dans le répertoire web (`htdocs` ou `www`).
3. **Base de données** :
   - Ouvrir phpMyAdmin (ou autre client MySQL).
   - Créer une base de données nommée `ibant_pay`.
   - Importer le fichier situé dans `sql/ibant_pay.sql` (qui contient la structure exacte des tables `classe`, `etudiant`, et `paiement`).
4. **Configuration BDD** :
   Si vos identifiants MySQL diffèrent de l'installation par défaut, modifiez les accès dans `config/databas.php` :
   ```php
   $user = "root";
   $pass = "root123#"; // Modifiez selon votre configuration serveur
   ```
5. **Lancement** : 
   Accéder à l'application via votre navigateur :
   ```
   http://localhost/ibant_pay/
   ```

---

## Structure de la base de données
- **`classe`** : (id, codeClasse, libelleClasse)
- **`etudiant`** : (id, matriEt, nom, mail, photo, idClasse)
- **`paiement`** : (id, etudiant_id, numPaie, datePaie, montant, anneeAc)