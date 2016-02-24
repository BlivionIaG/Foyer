# API Restfull du Foyer

## Installation

### Configurer les permissions

Installer ACL si ce n'est pas déjà fait.

```
setfacl -R -m u:www-data:rwX www/
setfacl -dR -m u:www-data:rwx www/
```

### Configurer l'environnement de production

Installer composer.

```
composer install
```
* Changer les identifiants de la base de donnée dans le fichier ```config/config.php```

### Générer la doc

Installer npm et apidoc (npm install apidoc -g).

```
apidoc -i routes/ -o doc/
```
Vous pourrez y accéder à l'url votre api avec votre navigateur.

##Routes

<a target="_blank" href="http://foyer.p4ul.tk/api/doc/">Lien vers la doc</a>

##Bugs

* Si vous avez des erreurs 404 sur vos requètes HTTP cela vient sûrement d'un problème de rewriting dans votre conf apache.
* Ne pas oublier les droits pour l'upload de fichier.
