# Comment utiliser cette application

1- **Tout dabord, vous aurez besoin de:**

php7.2 >= 

de composer

un serveur smtp pour permètre la validation des adresses mail de vos abonnés.

2- **Clonez ce dépot sur votre machine et rendez-vous à la racine de celui-ci**

3- **Installation**

*Installer avec Make*

Rien de plus simple. A l'interieur de votre dossier donc, tapez la commande suivante

***make install***

Et voilà 

*Installation pas a pas*

Toujours à la racine de votre projet, tapez dans l'ordre les commands suivantes dans votre terminal

***composer install***

***php bin/console d:d:c***

***php bin/console d:s:u -f***

***php bin/console d:f:l***  Tapez entrée pour continuer

***php -S localhost:8000 -t public***

Et voilà

*Vous n'avez qu'a vous rendre sur l'url signifiée dans la dernière commande ci-dessu grace a votre navigateur* 

localhost:8000 #votre nouvelle application est installée

4- **Si vous avez besoin de vous connecter avec l'utilisateur de base, utilisez les identifiants suivants**

email: roukoumanouamidou@gmail.com

mot de passe: password

Enjoy
