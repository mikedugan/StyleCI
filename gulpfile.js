var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.sass('app.scss')
        .scripts([
            'vendor/bower_components/jquery/dist/jquery.js',
            'vendor/bower_components/jquery-timeago/jquery.timeago.js',
            'vendor/bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js',
            'resources/assets/js/app.js',
            'resources/assets/js/**/*.js',
        ], './', 'public/dist/js/app.js')
        .version(['dist/css/app.css', 'dist/js/app.js'])
        .publish("vendor/bower_components/font-awesome/fonts/", "public/fonts/");
});
