# Videotheque
Application de type media center mis en relation avec Senscritique.

**Le client et le serveur doivent être sur la même machine.** Utilisation purement locale.

En attendant de faire un readme digne de ce nom, l'application possède un tutoriel expliquant les bases.

Côté serveur, l'application n'utilise que du PHP, aucun besoin d'une base de donnée.

**Suite à une mise à jour il peut être nécessaire de supprimer votre fichier de configuration**

## Configuration
L'application utilise **cURL** et la fonction **get_mime_type**. Vous devez donc configurer votre serveur en conséquence.

### Sous Linux (testé sur Ubuntu 16.04)
Vérifiez que vous possédez les extensions suivantes : **php-curl**, **php-xml**. Si vous ne les possédez pas, installez les avec la commande **apt-get install** (exemple : `apt-get install php-curl`).

Accédez au fichier **php.ini** qui se trouve normalement dans **/etc/php/numero_version_php/cli/** (le chemin peut différer selon votre installation). Dans ce fichier, décommentez les lignes suivantes :

`extension=php_curl.dll`

`extension=php_fileinfo.dll`

Puis redémarrez le serveur web.

**Important** : pensez à donner les droits de lecture et écriture aux dossier **data/** et **cookie/** du répertoire de l'application, ainsi que les droits de lecture au dossier contenant vos vidéos (et son contenu) ! Vous pouvez utiliser les commandes `chmod` et `umask` pour ça.

### Sous Windows (testé sur 7 et 10)

Accédez au fichier **php.ini** qui se trouve normalement dans **C:\Program Files (x86)\EasyPHP-Devserver-16.1\eds-binaries\php\votre_version_php\** (le chemin peut différer selon votre installation). Dans ce fichier, décommentez les lignes suivantes :

`extension=php_curl.dll`

`extension=php_fileinfo.dll`

Si vous ne possédez pas ces fichiers dll, téléchargez-les.
Puis redémarrez le serveur web.

Si cette manipulation ne suffit pas, tentez la méthode décrite par le premier commentaire http://php.net/manual/fr/curl.installation.php :

Copiez vers le dossier **Windows\system32** les fichiers suivants: libssh2.dll, php_curl.dll, ssleay32.dll, libeay32.dll

Si votre serveur web est **Apache**, copiez vers le dossier **Apache24\bin** le fichier libssh2.dll
