# Mise à jour du site

## 📄 Mise à jour contenu 

La mise à jour du site se fait via le fichier config.json. Ce fichier est généré par un google sheet (cf code.gs dans le repertoire tools) et envoyé sur ce repo dans la branche main directement
Lien vers le google sheet: https://docs.google.com/spreadsheets/d/1xOMqaaWr9BcTrH-tYBC2RJwj8oEGU1gxrU518_QDq20/edit?gid=0#gid=0

Le google sheet propose plusieurs formats recupérables via $config:

Dans le google sheet avec un tableau # object puis une ligne par paramètre
```php
$config["object"]["param"]
```

Dans le google sheet avec un tableau #objectType puis une colonne par paramètre et une ligne par liste d'objet de type ObjectType
```php
foreach($config['ObjectType'] as $index => $membre): $membre["param"]
```

Dans le google sheet avec un tableau #objectType_document puis une ligne par paramètre que l'on peut retrouver avec un get
```php
//securité
$documentsAutorises = ['bilan', 'programme', 'statuts', 'projet'];

// Récupération du type de document depuis l'URL
$type = isset($_GET['type']) ? $_GET['type'] : 'bilan';

// Vérification que le type est autorisé
if (!in_array($type, $documentsAutorises)) {
    // Redirection vers la page d'accueil si le type n'est pas autorisé
    header('Location: index.php');
    exit;
}

$configKey = $type . '_document';
$docConfig = $config[$configKey];
$docConfig['param'])
```


## ⚙️ Mis en production

Une fois le fichier config.json poussé sur la branche main, l'ensemble des fichiers (json et PHP) sont poussés en FTP via une github action

## 🚧 Mode maintenance

* Pour mettre en maintenance le site, renommer index.php en v2.php
* Pour publier/ouvrir le site, renommer v2.php en index.php
