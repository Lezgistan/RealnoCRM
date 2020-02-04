const path = require('path')
const mix = require('laravel-mix')
const webpack = require('webpack')
const versionhash = require('laravel-mix')
require('laravel-mix-versionhash')


mix
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .versionHash()
    .disableNotifications()

let extract = [
    'vue',
    'vform',
    'axios',
    'vuex',
    'jquery',
    'popper.js',
    'vue-i18n',
    'vue-meta',
    'js-cookie',
    'bootstrap',
    'vue-router',
    'sweetalert2',
    'vuex-router-sync',
    '@fortawesome/vue-fontawesome',
    '@fortawesome/fontawesome-svg-core',
    'selectize',
    'dropzone',
    'vanilla-lazyload',
    '@fancyapps/fancybox',
    'cropperjs',
    'toastr2',
]


if (mix.inProduction()) {
    mix.version()
    mix.extract(extract)
}

mix.webpackConfig({
    plugins: [
        // new BundleAnalyzerPlugin()
        new webpack.ProvidePlugin({
            jQuery: 'jquery',
        })
    ],
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '~': path.join(__dirname, './resources/js')
        }
    },
    output: {
        filename:'js/[chunkhash].js',
        chunkFilename: 'js/[name].[chunkhash].js',
        publicPath: mix.config.hmr ? '//localhost:8080' : '/'
    }
})

mix.browserSync({
    proxy:
        {
            target: 'http://realno.ebsp.ru/',
            ws: true
        },
    logPrefix: 'realno.ebsp.ru',
    host: 'realno.ebsp.ru',
    open: false,
    notify: true,
    ghostMode: {
        clicks: true,
        forms: true,
        scroll: true
    }
})


