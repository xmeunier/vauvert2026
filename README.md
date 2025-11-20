# Mise à jour du site

## 📄 Mise à jour contenu 

La mise à jour du site se fait via le fichier config.json. Ce fichier est généré par un google sheet et envoyé sur ce repo dans la branche main


Lien vers le google sheet: https://docs.google.com/spreadsheets/d/1xOMqaaWr9BcTrH-tYBC2RJwj8oEGU1gxrU518_QDq20/edit?gid=0#gid=0

## ⚙️ Mis en production

Une fois le fichier config.json poussé sur la branche main, l'ensemble des fichiers (json et PHP) sont poussés en FTP via une github action

## 🚧 Mode maintenance

* Pour mettre en maintenance le site, renommer index.php en v2.php
* Pour publier/ouvrir le site, renommer v2.php en index.php
