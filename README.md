# Installer
```bash
$ composer install
```

# Configuration
Renomer le fichier ``conf.example.php`` en ``conf.php``


```php
<?php
/**
 * Configure
 */
$token = 'LETOKEN'; // TOKEN (peut être récupéré en affichant le flux RSS des issues)
$url = 'https://mon-gitlab.com'; // Adresse du gitlab sans le / à la fin
$projetID = 79; // ID du projet

```

# Lancer
Va créer un fichier ``issues.xlsx``
```
$ php getissues.php
```