# Generation de facture

## Installation

```sh
git clone 24eme/generation-facture
cd generation-facture
composer install
```

## Configuration

Copiez .env.example dans un fichier .env et remplissez les informations liées à l'emplacement du nexcloud.

Copiez les fichiers `src/config/invoice.php.example` et  `src/config/prices.php.example` dans `src/config/invoice.php` et `src/config/prices.php` et remplissez les.

Enfint, utilisez le template client `src/config/clients/example.yml` pour configurer l'adresse de chacun des clients.

## Utilisation

### Compactage des temps

La première étape consiste à générer un fichier pivot à partir de l'ensemble des temps de l'équipe afin de définir la facturation.

Utilisez :
```sh
php bin/compact.php <periode> <numero_facturation> <chemin_fichier>
```
afin d'obtenir un fichier compacté.

`<periode>` doit être au format AAAAMMJJ autrement dit si la facturation est celle de juin 2020 : 20200630

`<numero_facturation>` est le premier numéro de facture que vous éditerez (par exemple 120)

`<chemin_fichier>` est le chemin du fichier temps global du mois. Il est préférable de posseder le projet temps et d'utiliser le fichier temps.csv après avoir effectuer un `git pull`

Le resultat est de la forme :

```sh
Client: monclient1 - Invoice: 2020063000120 - Presta: 2
Client: monclient2 - Invoice: 2020063000121 - Presta: 1
File : chemin-du-projet/out/compacted.csv.5f28754215b226
```

C'est dans ce fichier compacted.csv.5f28754215b226 que vous aller travailler pour remettre en forme les données.

### Génération des factures

Une fois le travail de remise en forme des données (attention à respecter vos numéros de facture) vous pouvez générer vos factures avec la commande suivante :

```sh
php bin/generate.php <chemin_fichier_csv> <chemin_dossier_sortie_pdf> [<chemin_dossier_sortie_mail>]
```

`<chemin_fichier_csv>` Chemin vers le fichier csv contenant les informations des factures à générer

`<chemin_dossier_sortie_pdf>` est le chemin vers le dossier dans lequel les pdf des factures vont être généré

`<chemin_dossier_sortie_mail>` est le chemin vers le dossier dans lequel les template de mails vont être générés. (optionnel)

Le resultat est le suivant :
```sh
Nouvelle facture dans : /chemin-du-nextcloud/2020063000120_FactureNomDeVotreSociete_Client1.pdf
Nouvelle facture dans : /chemin-du-nextcloud/2020063000121_FactureNomDeVotreSociete_Client2.pdf
```
