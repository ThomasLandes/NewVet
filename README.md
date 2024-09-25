
# NEW VET

Le projet NewVET est une plateforme d'e-commerce dédiée à la mode féminine, conçue pour offrir une expérience d'achat en ligne élégante et rapide.

Le site propose une sélection de vêtements de haute qualité, fabriqués en France, tout en mettant un accent particulier sur l'éthique et le respect de l'environnement.

Ce projet vise à combiner le luxe et la conscience écologique dans une interface utilisateur moderne et intuitive.




## Tester localement

Cloner le projet 


```bash
  git clone https://github.com/ThomasLandes/NewVet.git
```

Ce placer dans le dossier PHP du projet

```bash
  cd newvet/PHP
```

Installer PHP mailer à cet endroit via le github ou composer

////////////

Utiliser ensuite le script sql le plus récent pour creer et populer votre base de donnée

////////////

Il ne vous reste plus qu'a configurer mail.php en entrant les valeurs pour : 

`$mail->Username`
, `$mail->Password`
& ` $mail->setFrom`

le projet est pret à être utilisé. 







## Fonctionnalités

Fonctionnalités de base pour un site de ecommerce 
- En tant que visiteur: 

        - possibilité de naviguer sur l'interface , et d'ajouter des produits au panier
        - Contacter le support via contact.php

- En tant qu'utilisateur


Comme visiteur + 


        - Gestion de ses données via compte.php (info perso, adresse, carte de paiement)
        - Possibilité de commander un/plusieurs articles
        - Suivi et historique de commande depuis la page compte.php

- En tant qu'Administrateur
    

Comme Utilisateur + 

        - Accès au backoffice
        - Gestion Accueil
        - Gestion Produit
        - Gestion Categorie
        - Gestion Materiau
        - Gestion Commande
        - Gestion Contact

l'accès au backoffice est sécurisé via autoriserAdminOnly() qui redirige systematiquement => un utilisateur non connecté ET les utilisateurs connectés ayant un role différent de ADMIN 





        


## Info de fonctionnement 

le mail de reception à l'inscription peut : 

    - subir un problem lié au SMTP, dans ce cas vérifier que la mfa ne pose pas problème sur le compte utilisé

    - finir dans les SPAM, en effet le mail est signalé comme demarchage par la plupart des serveur mail

vous pouvez valider la creation d'un utilisateur directement depuis la BDD. 

**//\\\\**

la commande manque d'optimisation, en tant que visiteur vous etes redirigé vers la connexion/creation de compte, puis vous devez enregistrer vos infos avant de pouvoir passer une commande. 

**//\\\\** 

le panier, géré via cookie est supprimé systematiquement a la deconnexion de l'utilisateur (pas de synchronisation du panier multiplateforme/multisupport)

**//\\\\** 

le systeme de paiement est fictif, il n'y a pas de validation des données de CB, pas de validation par des api bancaire/tier de confiance 








