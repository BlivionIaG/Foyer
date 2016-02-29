# API RESTful du Foyer

## Installation

### Configurer les permissions

Installer ```ACL``` si ce n'est pas déjà fait.

```
setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX www/
setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx www/
```

### Configurer l'environnement de production

Installer ```composer```.

```
composer install
```
* Changer les identifiants de connexion à la base de donnée dans le fichier ```config/config.php```.

### Génération de la doc

Installer ```npm``` et ```apidoc``` avec la commande ```npm install apidoc -g```.

```
apidoc -i routes/ -o doc/
```
Vous pourrez ensuite y accéder par l'url de votre api.

## Routes

<a target="_blank" href="http://foyer.p4ul.tk/api/doc/">Lien vers la doc</a>

## Bugs

* Si vous avez des erreurs 404 sur vos requètes HTTP cela vient sûrement d'un **problème de rewriting**. Voir votre configuration apache.
* Ne pas oublier les **droits ```www-data```** pour faire fonctionner l'upload de fichier.
