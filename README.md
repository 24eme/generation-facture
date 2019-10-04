# Generation de facture

## Installation

```sh
git clone 24eme/generation-facture
cd generation-facture
composer install
```

## Configuration

Copy .env.example to .env, and fill it with the repo informations.

Then, copy `src/config/invoice.php.example` to `src/config/invoice.php` and fills it.

At last, use the client template `src/config/clients/example.yml` to generate config file for your clients.

## Usage

Use `bin/download.php <periode>` to download the csv files from github, and `bin/generate.php <periode>` to generate the invoices.

You can specify a list of name to generate with the `--name=` option.
