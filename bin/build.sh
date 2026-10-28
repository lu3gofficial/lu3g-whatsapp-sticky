#!/bin/sh
# Crea dist/lu3g-whatsapp-sticky.zip (cartella lu3g-whatsapp-sticky/ all'interno), il pacchetto che WordPress installa e aggiorna.
# Esclude i file che servono solo allo sviluppo (git, script, automazioni).
set -e
cd "$(dirname "$0")/.."
rm -rf dist && mkdir -p dist/lu3g-whatsapp-sticky
cp -R lu3g-whatsapp-sticky.php readme.txt includes widgets assets dist/lu3g-whatsapp-sticky/
(cd dist && zip -rq lu3g-whatsapp-sticky.zip lu3g-whatsapp-sticky -x '*.DS_Store')
rm -rf dist/lu3g-whatsapp-sticky
echo "dist/lu3g-whatsapp-sticky.zip"
