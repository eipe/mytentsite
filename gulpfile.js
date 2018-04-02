var elixir = require('laravel-elixir'),
    webpack = require('laravel-elixir-webpack-official'),
    gulp = require('gulp');

var config = {
    nodeDir: '../../../node_modules'
};

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

elixir(function(mix) {
    mix.sass('app.scss', 'public/css/app.css');

    mix.styles('app_normalize.css', 'public/css/app_normalize.css')
        .styles([
            config.nodeDir + '/font-awesome/css/font-awesome.css',
            config.nodeDir + '/animate.css/animate.css'
        ], 'public/css/core-dep.css')
        .styles([
            config.nodeDir + '/leaflet/dist/leaflet.css',
            config.nodeDir + '/leaflet.markercluster/dist/MarkerCluster.css',
            config.nodeDir + '/leaflet.markercluster/dist/MarkerCluster.Default.css',
            config.nodeDir + '/Leaflet.Photo/Leaflet.Photo.css',
            config.nodeDir + '/leaflet-easybutton/src/easy-button.css',
        ], 'public/css/map-dep.css');

    mix.webpack('app.js', 'public/js/app.js')
        .scripts([
            config.nodeDir + '/vue/dist/vue.js',
            config.nodeDir + '/vue-router/dist/vue-router.js',
            config.nodeDir + '/axios/dist/axios.js',
            config.nodeDir + '/vuex/dist/vuex.js'
        ], 'public/js/core-dep.js')
        .scripts([
            config.nodeDir + '/leaflet/dist/leaflet.js',
            config.nodeDir + '/leaflet.markercluster/dist/leaflet.markercluster.js',
            config.nodeDir + '/Leaflet.Photo/Leaflet.Photo.js',
            config.nodeDir + '/leaflet-easybutton/src/easy-button.js',
        ], 'public/js/map-dep.js')
        .scripts([
            config.nodeDir + '/jquery/dist/jquery.js',
            config.nodeDir + '/exif-js/exif.js',
            config.nodeDir + '/cropit/dist/jquery.cropit.js',
        ], 'public/js/share-dep.js');
});

gulp.task('app-images', function() {
    return gulp.src('resources/assets/images/*.*').
        pipe(gulp.dest('public/css/images'));
});

gulp.task('move-component-assets', ['app-images']);