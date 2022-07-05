## Folder directories

`tailwind` theme folder comes from `web/themes/custom/`.

`jobmodule` module folder comes from `web/module/custom/`.

`config` DB folder comes from Drupal `root` directory.

## How to run Tailwindcss

1. Go to `retro` theme root directory.

2. Run 
```
npm install -D tailwindcss
```
3. Run
```
npx tailwindcss --input src/tailwind.css --output dist/tailwind.css --watch
```

## How to use `config` DB folder.
Run
```
drush cim
```
