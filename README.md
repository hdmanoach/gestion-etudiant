# Gestion Étudiant

Ce projet est une application web de gestion d'étudiants développée en PHP. Elle permet de gérer les informations des étudiants, leurs inscriptions et leurs notes. L'application intègre plusieurs fonctionnalités de sécurité robustes pour protéger les données et contrôler les accès.

## Fonctionnalités

*   Gestion des étudiants (CRUD)
*   Gestion des inscriptions
*   Gestion des notes
*   Système d'authentification des utilisateurs
*   Tableaux de bord pour les administrateurs et les utilisateurs

## Sécurité

Le projet a été développé en tenant compte des meilleures pratiques de sécurité, notamment pour contrer les vulnérabilités du **TOP 10 de l'OWASP**.

### 1. Contrôle d'accès robuste (Broken Access Control)

*   **Authentification obligatoire** : La plupart des pages nécessitent une connexion. Le fichier `include/session.php` vérifie si un utilisateur est connecté avant d'autoriser l'accès.
*   **Accès basé sur les rôles (RBAC)** : Le système définit des rôles (par exemple, "super admin" et "utilisateur simple"). Après la connexion, les utilisateurs sont redirigés vers des tableaux de bord différents en fonction de leur rôle.

### 2. Prévention des injections (Injection)

*   **Requêtes préparées** : Toutes les requêtes SQL sont exécutées à l'aide de requêtes préparées avec PDO. Cela élimine les risques d'injection SQL en séparant les instructions SQL des données.

### 3. Gestion des mots de passe (Cryptographic Failures)

*   **Hachage des mots de passe** : Les mots de passe des utilisateurs sont hachés avec l'algorithme `PASSWORD_DEFAULT` de PHP (`password_hash`), qui est actuellement bcrypt. Cela garantit que les mots de passe ne sont jamais stockés en clair.

### 4. Authentification à deux facteurs (Identification and Authentication Failures)

*   **Double authentification (2FA)** : Une authentification à deux facteurs est mise en place. Après avoir entré son nom d'utilisateur et son mot de passe, l'utilisateur reçoit un code de vérification à usage unique par e-mail qu'il doit saisir pour finaliser la connexion.

### 5. Protection contre le Cross-Site Scripting (XSS)

*   **Échappement des données** : Les données provenant de la base de données ou des utilisateurs sont systématiquement échappées à l'aide de `htmlspecialchars()` avant d'être affichées dans le HTML, empêchant ainsi l'exécution de scripts malveillants.

### 6. Journalisation et surveillance (Logging and Monitoring)

*   **Traçabilité des activités** : Un système de journalisation en temps réel enregistre toutes les actions significatives des utilisateurs (connexions, modifications, suppressions, etc.). Ces logs sont essentiels pour la surveillance, la détection d'incidents et l'audit de sécurité.

## Installation

1.  Clonez le dépôt.
2.  Configurez votre serveur web (Apache, Nginx) pour qu'il pointe vers le répertoire du projet.
3.  Importez la base de données (schéma non inclus dans ce README).
4.  Configurez les variables d'environnement dans un fichier `.env` à la racine du projet (pour la base de données et les identifiants SMTP).
5.  Installez les dépendances avec Composer : `composer install`

