var elixir = require('laravel-elixir'),
    gulp = require('gulp');

var config = {
    bowerDir: '../../../vendor/bower_components'
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
    //mix.sass('app.scss', 'public/css/app.css');

    mix.styles('app.css', 'public/css/app.css')
        .styles('app_normalize.css', 'public/css/app_normalize.css')
        .styles([
        config.bowerDir + '/leaflet/dist/leaflet.css',
        config.bowerDir + '/leaflet.markercluster/dist/MarkerCluster.css',
        config.bowerDir + '/leaflet.markercluster/dist/MarkerCluster.Default.css',
        config.bowerDir + '/Leaflet.Photo/Leaflet.Photo.css',
        config.bowerDir + '/Leaflet.EasyButton/src/easy-button.css',
        config.bowerDir + '/font-awesome/css/font-awesome.css',
        config.bowerDir + '/foundation-sites/dist/foundation-flex.css',
        config.bowerDir + '/foundation-sites/dist/foundation.css'
    ], 'public/css/vendor.css');

    mix.scripts('app.js', 'public/js/app.js')
        .scripts([
        config.bowerDir + '/jquery/dist/jquery.js',
        config.bowerDir + '/leaflet/dist/leaflet.js',
        config.bowerDir + '/leaflet.markercluster/dist/leaflet.markercluster.js',
        config.bowerDir + '/Leaflet.Photo/Leaflet.Photo.js',
        config.bowerDir + '/Leaflet.EasyButton/src/easy-button.js',
        config.bowerDir + '/foundation-sites/dist/foundation.js',
        config.bowerDir + '/exif-js/exif.js',
        config.bowerDir + '/what-input/what-input.js'
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