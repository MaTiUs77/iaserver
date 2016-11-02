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
        gulp --production

    #5 Ser feliz!
 */

var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.scriptsIn(
        "resources/views/aoicollector/prod/assets/js",
        "public/vendor/aoicollector/prod/prod.js"
    );

    mix.scriptsIn(
        "resources/views/iaserver/assets/js",
        "public/vendor/iaserver/iaserver.js"
    );
});
