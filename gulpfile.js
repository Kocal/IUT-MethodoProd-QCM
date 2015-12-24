var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.js.coffee.options.bare = true;

elixir(function(mix) {
    mix.browserSync({
        proxy: 'qcm.app:8000'
    });

    mix.sass('all.scss');

    mix.coffee([
        '../coffee/*.coffee'
    ], 'resources/assets/js/compiled.coffee.js');

    mix.scripts([
        'font-size-bug.fix.js',
        'jquery-2.1.1.js',
        'mustache.js',
        'compiled.coffee.js'
    ]);

    mix.version(['css/all.css', 'js/all.js']);
});
