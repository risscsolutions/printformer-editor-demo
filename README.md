# Editor Client API Demo

## Folder structure

| Foldername   |      Function      |
|----------|:-------------:|
| dist |  parcel build folder |
| src |  code of whole project |
| src/components |  all vue components are here |
| src/styles |  this folder is for all the styles like css or scss files |

## Demo Installation

First we clone this repository:

```bash
git clone https://github.com/rissc/printformer-editor-demo.git
``` 

To use the demo application we need some enviroment variables. The `.env.example` shows which variables we need.
```bash
PRINTFORMER_URL = ''
PRINTFORMER_USER = ''
PRINTFORMER_BEARER_TOKEN = ''
PRINTFORMER_CLIENT_IDENTIFIER = ''
PRINTFORMER_TEMPLATE_IDENTIFIER = ''
PRINTFORMER_INTENT = ''
NPM_REGISTRY_TOKEN = ''
```
After filling out all vars we can create a `.env` file or copy the `.env.example`:

```bash
cp .env.example .env 
```
The `NPM_REGISTRY_TOKEN` in the `.npmrc` is needed for authentication with our NPM-Registry. `npm install` will only work if you've been authenticated.

After that you can safely run `npm install` without authentication issues. For the php dependencies is composer used. Just run `composer install`

The `Procfile` is only needed for Heroku deployments to change the apache2 documentroot to `dist` <br>

### **Here is a working Demo: https://blooming-peak-75988.herokuapp.com/**

## Local Development 
You can use the predefined `npm run dev` command. The parcel-bundler will automatically build and watch the files for changes.
```bash
npm run dev
# or
parcel index.html --no-hmr
```
It is also required to run a local php server in the `dist` folder. On a mac you can do this with:
```
cd dist/
```
```
php -S localhost:9000
```
The project is now available at `http://localhost:9000`   
