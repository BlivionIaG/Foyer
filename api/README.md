# API RESTful du Foyer

## Installation

Installer <a target="_blank" href="https://getcomposer.org/download/">```composer```</a> puis lancer la commande suivante pour installer l'api.

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
Vous pourrez ensuite y accéder par l'url de votre api.

## Routes

<a target="_blank" href="http://foyer.p4ul.tk/api/doc/">Lien vers la doc</a>

## Bugs

* Si vous avez des erreurs 404 sur vos requètes HTTP cela vient sûrement d'un **problème de rewriting**. Voir votre configuration apache.
* Ne pas oublier les **droits ```www-data```** pour faire fonctionner l'upload de fichier.
* Ne pas oublier le **header Authorization** pour vous connecter à l'api.
