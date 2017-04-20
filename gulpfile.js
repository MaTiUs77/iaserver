/*
    Para instalar Gulp y Elixir

    #1 Verificar si estan instalados Node y Npm
        node -v
        npm -v

    #2 Instalar Gulp
        npm install --global gulp-cli

    #3 Instalar Elixir
        npm install --no-bin-links

    #4 Editar este archivo y ejecutar
        gulp watch --production

    #5 Ser feliz!
 */

var elixir = require('laravel-elixir');

elixir(function(mix) {

    // === IASERVER
    mix.scriptsIn(
        "resources/views/iaserver/assets/js",
        "public/vendor/iaserver/iaserver.js"
    );

    // === AOICOLLECTOR
    mix.scriptsIn(
        "resources/views/aoicollector/prod/assets/js",
        "public/vendor/aoicollector/prod/prod.js"
    ).scriptsIn(
        "resources/views/aoicollector/stat/assets/js",
        "public/vendor/aoicollector/stat/stat.js"
    ).scriptsIn(
        "resources/views/aoicollector/inspection/assets/js",
        "public/vendor/aoicollector/inspection/inspection.js"
    ).scriptsIn(
        "resources/views/trazabilidad/assets/js",
        "public/vendor/trazabilidad/trazabilidad.js"
    ).scriptsIn(
        "resources/views/aoicollector/monitor/assets/js",
        "public/vendor/aoicollector/monitor/aoicollectormonitor.js"
    );

    // === SERVERMONITOR
    mix.scriptsIn(
        "resources/views/servermonitor/assets/js",
        "public/vendor/servermonitor/servermonitor.js"
    );

    // === MOLINETE
    mix.scriptsIn(
        "resources/views/molinete/assets/js",
        "public/vendor/molinete/molinete.js"
    );
});
