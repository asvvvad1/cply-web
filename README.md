# cply-web
Easily search, copy and view lyrics

## Installation

* Clone the repository `git clone https://github.com/asvvvad/cply-web` or download the [zip](https://github.com/asvvvad/cply-web/zipball/master/) and extract to your web server public directory [venodr dir. help](http://stackoverflow.com/questions/25192681/ddg#25193426)
* run `composer install` in the folder to install dependencies
* as with my other CPLY implementations, it requires an _access token_ for the serach functionality, which you can generate [here](https://genius.com/api-clients)
Copy the token and paste it in place of `acess_token` [index.php @ line 19](index.php#L19)
* Done!

## Demo: [Here!](https://asvvvad.eu.org/cply-web/)

## All CPLY implementations: 
* [cply-php](https://github.com/asvvvad/cply-php): original cli php script
* [cply](https://github.com/asvvvad/cply): go cli implementation, better
* [cply-web](https://github.com/asvvvad/cply-web): Browser version built on same code from original php cli version and javascript to copy the lyrics
