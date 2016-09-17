# Videotheque
Application de type media center mis en relation avec Senscritique.
Le client et le serveur doivent être sur la même machine. Utilisation purement locale.

En attendant de faire un readme digne de ce nom, l'application possède un tutoriel expliquant les bases.

Côté serveur, l'application n'utilise que du PHP, aucun besoin d'une base de donnée.

A l'heure actuelle n'a été testé que sur Windows sur Chrome.

## Configuration du php.ini
L'application utilise **Curl** et la fonction **get_mime_type**.

### Sous Windows
Il faut donc, dans le **php.ini**, décommenter les lignes suivantes :

`extension=php_curl.dll`

`extension=php_fileinfo.dll`

puis redémarrer le serveur web. Si vous ne posséder pas ces dll, téléchargez-les.

Si cette manipulation ne suffit pas, tentez la méthode décrite par le premier commentaire http://php.net/manual/fr/curl.installation.php :

Copiez vers le dossier **Windows\system32** les fichiers suivants: libssh2.dll, php_curl.dll, ssleay32.dll, libeay32.dll

Si votre serveur web est **Apache**, copiez vers le dossier **Apache24\bin** le fichier libssh2.dll
