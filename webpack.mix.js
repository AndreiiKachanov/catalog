require('dotenv').config();
const mix = require('laravel-mix');
const env = process.env.APP_ENV;

// Если в .env параметр APP_ENV = local, файлы компилируются в public/build,
// иначе в public/

// пути для окружения dev и production - (env === 'dev' || env === 'production')
let publicPath = 'public';
let resourceRoot = './';
let outputDir = 'public/';
let fontsAwesomePath = '/fonts';


if (env === 'local') {
    publicPath = 'public/build';
    resourceRoot = '/build/';
    outputDir = './';
    fontsAwesomePath = '/build/fonts';
}

console.log('inProduction', mix.inProduction());


mix
    .setPublicPath(publicPath)
    .setResourceRoot(resourceRoot)
    .js('resources/js/app.js', outputDir + 'js')
    .js('resources/js/app_admin.js', outputDir + 'js')
    .sourceMaps(false, 'source-map')
    //fix error 404 on nginx - иначе шрифты на VPS не грузятся из папки vendor
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', publicPath + '/fonts')
    // additionalData - нужен для исправления ошибки со шрифтами, где на проде nginx не видит папку /vendor...
    .sass('resources/sass/app.scss', outputDir + 'css', {
        additionalData: '$fontsAwesomePath:\'' + fontsAwesomePath  + '\';'
    })
    .vue({
            extractStyles: false,
            globalStyles: "resources/sass/vue_components.scss"
        }
    ).version();

if (env === 'local') {
    mix.browserSync({
        // logLevel: "debug",
        open: false,
        proxy: 'https://' + 'nginx',
        // proxy: 'nginx',
        // host: '192.168.0.109',
        // proxy: {
        //     target: "nginx", // replace with your web server container
        //     proxyReq: [
        //         function(proxyReq) {
        //             proxyReq.setHeader('HOST', 'larka_new.loc'); // replace with your site host
        //         }
        //     ]
        // },
        https: true,
        // https: false,
        watchOptions: {
            usePolling: true,
            interval: 500,
            ignored: /node_modules/,
        },
        files: [
            'resources/views/**/*.php',
            'resources/js/app.js',
            'resources/js/app_admin.js',
            'resources/js/components/*.vue',
            'public/js/**/*.js',
            'public/build/js/**/*.js',
            'public/css/**/*.css',
            'public/build/css/**/*.css',
        ],
        notify: true,
    });
} else {
    console.log('Env != local!!!! browserSync now working')
}

