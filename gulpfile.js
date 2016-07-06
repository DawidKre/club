var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

var config = {
    assetsDir: 'src/Club/BlogBundle/Resources/public',
    production: !!plugins.util.env.production,
    sourceMaps: !plugins.util.env.production
};

var app = {};

app.addStyle = function (paths, filename, dest) {
    gulp.src(paths)
        .pipe(plugins.plumber())
        .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.init()))
        .pipe(plugins.concat(filename))
        .pipe(config.production ? plugins.cleanCss() : plugins.util.noop())
        .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.write('.')))
        .pipe(gulp.dest(dest));
};

app.addScript = function (paths, filename, dest) {
    gulp.src(paths)
        .pipe(plugins.plumber())
        .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.init()))
        .pipe(plugins.concat(filename))
        .pipe(config.production ? plugins.uglify() : plugins.util.noop())
        .pipe(plugins.if(config.sourceMaps, plugins.sourcemaps.write('.')))
        .pipe(gulp.dest(dest));
};

app.copy = function (srcFiles, outputDir) {
    gulp.src(srcFiles)
        .pipe(gulp.dest(outputDir))
};

gulp.task('css', function () {
    app.addStyle([
        config.assetsDir + '/' + '*.css'
    ], 'main.css', 'web/public');
    app.addStyle([
        config.assetsDir + '/' + 'css/*.css'
    ], 'packages.css', 'web/public/css');
});

gulp.task('js', function () {
    app.addScript([
        config.assetsDir + '/' + 'js/jquery-1.11.1.js',
        config.assetsDir + '/' + 'js/*.js',
        config.assetsDir + '/' + 'js/custom.js'
    ], 'site.js', 'web/public/js')

});

gulp.task('fonts', function () {
    app.copy(config.assetsDir + '/fonts/awesome/*',
        'web/public/fonts/awesome'
    );
});

gulp.task('images', function () {
    app.copy(config.assetsDir + '/images/*',
        'web/public/images'
    );
});


gulp.task('default', ['css', 'js', 'fonts', 'images']); 