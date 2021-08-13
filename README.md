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

`git clone https://github.com/rissc/printformer-editor-demo.git` <br><br>
To use the demo application we need some enviroment variables The `.env.example` shows which variables we need.
<br>
<br>
`PRINTFORMER_URL = ''`<br>
`PRINTFORMER_USER = ''`<br>
`PRINTFORMER_BEARER_TOKEN = ''`<br>
`PRINTFORMER_CLIENT_IDENTIFIER = ''`<br>
`PRINTFORMER_TEMPLATE_IDENTIFIER = ''`<br>
`PRINTFORMER_INTENT = ''`<br>
`NPM_REGISTRY_TOKEN = ''`<br>
<br>
After filling out all vars we can create a `.env` file or copy the `.env.example`:

`cp .env.example .env `<br><br>
The `NPM_REGISTRY_TOKEN` in the `.npmrc` is needed for authentication with our NPM-Registry. `npm install` will only work if you've been authenticated.

After that you can safely run `npm install` without authentication issues. For the php dependencies is composer used. Just run `composer install`

The `Procfile` is only needed for Heroku deployments to change the apache2 documentroot to `dist` <br>

### **Here is a working Demo: https://blooming-peak-75988.herokuapp.com/**

## Local Development 
You can use the predefined `npm run dev` command. The parcel-bundler will automatically build and watch the files for changes.

`npm run dev`<br>
`# or` <br>
`parcel index.html --no-hmr`<br><br>
It is also required to run a local php server in the `dist` folder. On a mac you can do this with:

`cd dist/` <br>
`php -S localhost:9000`<br><br> 
The project is now available at `http://localhost:9000`   
