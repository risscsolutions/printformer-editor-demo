# Editor Client API Demo

## Ordnerstruktur

| Ordnername   |      Funktion      |
|----------|:-------------:|
| dist |  Der Build Ordner von Parcel |
| src |  Hier liegt der Code der ganzen Demo |
| src/components |  In diesem Ordner befinden sich alle einzelnen vue-componenten|
| src/styles |  Hier werden alle style Dateien von css oder scss abgelegt und in der index.html eingebunden


## Demo Installieren

Als erstes wird das Repository von Github wie folgt installiert:

`git clone https://github.com/rissc/printformer-editor-demo.git` <br><br>
Damit die Demo-Anwendung genutzt werden kann, werden ein paar Environment-Variablen benötigt. In der `.env.example` ist<br>
vorgegeben, welche Variablen benötigt werden.
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
Wenn alle Informationen für die Variablen da sind, speichert man alles in einer neuen<br> Datei `.env` ab oder kopiert die
`.env.example`:

`cp .env.example .env `<br><br>
Der `NPM_REGISTRY_TOKEN` muss zusätzlich in der `.npmrc` mit dem Platzhalter `"NPM_REGISTRY_TOKEN"` <br>
ausgetauscht werden. Nur dann können die nötigen privaten Pakete von der rissc-NPM-Registry installiert werden.

Anschließend kann npm install ausgeführt werden, um alle nötigen Javascript-Dependencies zu installieren. Danach werden<br>
noch die notwendigen php-composer Pakete mit composer install installiert.

Die `Procfile` wird nur für ein Deployment bei Heroku genutzt, um den apache2 documentroot auf den `dist` Ordner zu legen.<br>
###**Eine funktionstüchtige Demoanwendung ist hier: https://blooming-peak-75988.herokuapp.com/**

## Lokale Weiterentwicklung 
Werden nun Änderungen im Code vorgenommen, kann man npm run dev verwenden. Hier wird im <br>
Hintergrund der Parcel Watcher auf die index.html Datei gesetzt.

`npm run dev`<br>
`# oder` <br>
`parcel index.html --no-hmr`<br><br>
Außerdem ist es notwendig einen php-server im dist Ordner zu starten. Man wechselt zuerst in
das dist Verzeichnis mit:

`cd dist/` <br><br>
Und startet danach den php-server:

`php -S localhost:9000`<br><br> 
Das Projekt ist nun unter `http://localhost:9000` erreichbar.   
