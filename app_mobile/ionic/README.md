# Application Ionic

## Installation

Installer les paquets manquants en utilisant la commande :
``` bower install ```

Démarrer le serveur en utilisant la commande :
``` ionic serve ```

## Ajouter des platformes

``` ionic platforms [ios,android] ```

## Build de l'application

``` cordova build --release [ios,android] ```

### Android Build

Généreration de la clé privée :
``` keytool -genkey -v -keystore my-release-key.keystore -alias alias_name -keyalg RSA -keysize 2048 -validity 10000 ```
Signature de l'APK :
``` jarsigner -verbose -sigalg SHA1withRSA -digestalg SHA1 -keystore my-release-key.keystore HelloWorld-release-unsigned.apk alias_name ```
Optimization de l'APK :
``` zipalign -v 4 HelloWorld-release-unsigned.apk HelloWorld.apk ```

## Interface Admin

Disponibles à cette adresse : <a href="https://apps.ionic.io/" target="_blank">apps.ionic.io</a>

