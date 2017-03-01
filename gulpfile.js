var elixir = require('laravel-elixir'),
    webpack = require('laravel-elixir-webpack-official'),
    gulp = require('gulp');

var config = {
    bowerDir: '../../../vendor/bower_components',
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
        config.bowerDir + '/leaflet/dist/leaflet.css',
        config.bowerDir + '/leaflet.markercluster/dist/MarkerCluster.css',
        config.bowerDir + '/leaflet.markercluster/dist/MarkerCluster.Default.css',
        config.bowerDir + '/Leaflet.Photo/Leaflet.Photo.css',
        config.bowerDir + '/Leaflet.EasyButton/src/easy-button.css',
        config.bowerDir + '/font-awesome/css/font-awesome.css'
    ], 'public/css/vendor.css');

    mix.webpack('app.js', 'public/js/app.js')
        .scripts([
        config.bowerDir + '/jquery/dist/jquery.js',
        config.bowerDir + '/leaflet/dist/leaflet.js',
        config.bowerDir + '/leaflet.markercluster/dist/leaflet.markercluster.js',
        config.bowerDir + '/Leaflet.Photo/Leaflet.Photo.js',
        config.bowerDir + '/Leaflet.EasyButton/src/easy-button.js',
        config.bowerDir + '/exif-js/exif.js',
        config.bowerDir + '/what-input/dist/what-input.js',
        config.bowerDir + '/cropit/dist/jquery.cropit.js',
        config.nodeDir + '/vue/dist/vue.js',
        config.nodeDir + '/vue-progressive-image/dist/vue-progressive-image.js',
        config.nodeDir + '/vue-router/dist/vue-router.js',
        config.nodeDir + '/axios/dist/axios.js',
        config.nodeDir + '/vuex/dist/vuex.js'
    ], 'public/js/vendor.js');
});

gulp.task('font-awesome', function() {
    return gulp.src('vendor/bower_components/font-awesome/fonts/*.*')
        .pipe(gulp.dest('public/fonts'));
});

gulp.task('leaflet-images', function() {
    return gulp.src('vendor/bower_components/leaflet/dist/images/*.*').
        pipe(gulp.dest('public/css/images'));
});

gulp.task('app-images', function() {
    return gulp.src('resources/assets/images/*.*').
        pipe(gulp.dest('public/css/images'));
});

gulp.task('move-component-assets', ['font-awesome', 'leaflet-images', 'app-images']);