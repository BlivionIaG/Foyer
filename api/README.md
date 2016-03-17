# API RESTful du Foyer

## Installation

Installer <a href="https://getcomposer.org/download/" target="_blank">```composer```</a> puis lancer la commande suivante pour installer l'api.

```
composer install
```
* Suivez l'installation. Après l'installation vos paramètres générés sont éditables dans le fichier suivant ```config/config.yml```.

## Génération de la doc

Installer ```npm``` avec les commandes suivantes ```sudo apt-get install npm``` et ```sudo npm install npm -g```.
Puis installer ```apidoc``` avec la commande ```npm install apidoc -g```.

```
apidoc -i routes/ -o doc/
```
Vous pourrez ensuite y accéder via l'url de votre api.

## Routes

<a href="http://foyer.p4ul.tk/api/doc/" target="_blank">Lien vers la doc</a>

## Bugs

* Si vous avez des erreurs 404 sur vos requètes HTTP cela vient sûrement d'un **problème de rewriting**. Voir votre configuration apache.
* Ne pas oublier les **droits ```www-data```** pour faire fonctionner l'upload de fichier.
* Ne pas oublier le **header Authorization** pour requêter à l'api.
