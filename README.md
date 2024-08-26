# NewVet
Site e-commerce | Epreuve finale du BSI Limayrac
Introduction
Le projet NewVET est une plateforme d'e-commerce dédiée à la mode féminine, conçue pour offrir une expérience d'achat en ligne élégante et rapide. Le site propose une sélection de vêtements de haute qualité, fabriqués en France, tout en mettant un accent particulier sur l'éthique et le respect de l'environnement. Ce projet vise à combiner le luxe et la conscience écologique dans une interface utilisateur moderne et intuitive.

Fonctionnalités
Gestion du carrousel d'images : Permet de mettre à jour les images du carrousel de la page d'accueil via un formulaire dans le back-office.
Mise en avant des catégories : Sélectionnez les catégories à mettre en avant sur la page d'accueil.
Gestion des produits "Highlanders" : Choisissez les produits vedettes à afficher sur la page d'accueil.
Navigation dynamique : Une interface utilisateur réactive avec des éléments visuels tels que des icônes, des carrousels et des cartes de produits.
Backend administrable : Un back-office qui permet aux administrateurs de gérer le contenu affiché sur la page d'accueil.
Structure du Projet
Le projet est organisé selon une structure MVC simple, avec une séparation claire entre la logique métier, l'interface utilisateur, et la gestion de la base de données.

Prérequis
Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

PHP 7.4+
MySQL 5.7+
Apache/Nginx (ou tout autre serveur compatible PHP)
Composer (facultatif, si vous souhaitez gérer des dépendances PHP)


Installation
Cloner le dépôt :

git clone https://github.com/votre-nom-utilisateur/newvet.git
cd newvet
Configurer la base de données :

Importez le fichier newvet.sql (ou un fichier similaire) pour créer les tables nécessaires.
Mettez à jour les informations de connexion à la base de données dans fonction/conf.php.
Configurer le serveur :

Assurez-vous que le serveur pointe vers le dossier PHP\FrontEnd\VIEW pour servir les pages front-end.
Configurez les permissions pour permettre le téléchargement d'images dans le dossier image/accueil/.
Utilisation
Front-end : Accédez à la page d'accueil via index.php pour voir le site en action.
Back-office : Accédez au backoffice/backoffice-index.php pour gérer les images du carrousel, les catégories mises en avant, et les produits "Highlanders".
