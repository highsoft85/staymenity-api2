const mix = require('laravel-mix');
const _ = require('lodash');
require('laravel-mix-svg-sprite');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const FILES = {
    app: {
        css: {
            from: 'resources/sass/app/app/app.scss',
            to: 'public/css/app.css',
        },
        js: {
            from: 'resources/js/app/app.js',
            to: 'public/js',
        },
        svg: {
            from: null,
            to: null,
        }
    },
    cmf: {
        css: {
            from: 'resources/sass/cmf/app/app.scss',
            to: 'css/cmf.css',
        },
        js: {
            from: 'resources/js/cmf/app.js',
            to: 'js/cmf.js',
        },
        svg: {
            from: 'resources/svg',
            to: 'public/svg/svg.svg',
        }
    }
};

const MIX = {

    mix: null,

    config: {
        css: {
            from: '',
            to: '',
        },
        js: {
            from: '',
            to: '',
        },
        svg: {
            from: null,
            to: null,
        },
    },

    run(mix) {
        let self = this;
        self.mix = _.clone(mix);
        return self;
    },
    app() {
        let self = this;
        self.config.css = FILES.app.css;
        self.config.js = FILES.app.js;
        self.config.svg = FILES.app.svg;

        self.buildFromTo(self.config.js, function (from, to) {
            self.buildJs(from, to)
        });
        return self;
    },
    cmf() {
        let self = this;
        self.config.css = FILES.cmf.css;
        self.config.js = FILES.cmf.js;
        self.config.svg = FILES.cmf.svg;

        // все файлы туда перекинутся
        self.mix.setPublicPath('public/cmf');

        // в css подставится префикс для изображений
        self.mix.setResourceRoot('/cmf');

        self.buildFromTo(self.config.js, function (from, to) {
            self.buildJs(from, to)
        });
        return self;
    },
    development(watchUrl) {
        let self = this;
        self.mix.webpackConfig({
            devtool: "inline-source-map"
        });
        self.buildFromTo(self.config.css, function (from, to) {
            self.buildSassDevelopment(from, to)
        });
        if (self.config.svg.from !== null) {
            //self.mix.svgSprite(self.config.svg.from, self.config.svg.to);
        }
        self.mix.browserSync(watchUrl);
        self.mix.disableNotifications();
    },
    production() {
        let self = this;
        self.buildFromTo(self.config.css, function (from, to) {
            self.buildSassProduction(from, to)
        });
        if (self.config.svg.from !== null) {
            //self.mix.svgSprite(self.config.svg.from, self.config.svg.to);
        }
    },
    buildFromTo(configFromTo, callback) {
        if (configFromTo instanceof Array) {
            configFromTo.map(function (item) {
                callback(item.from, item.to);
            });
        } else {
            callback(configFromTo.from, configFromTo.to);
        }
    },
    buildJs(from, to) {
        const self = this;
        self.mix.js(from, to);
    },
    buildSassDevelopment(from, to) {
        const self = this;
        self.mix.sass(from, to).options({
            autoprefixer: {
                options: {
                    browsers: [
                        'last 6 versions',
                    ]
                }
            },
            'postcss-url': {
                options: {
                    url: 'rebase',
                    useHash: false,
                }
            },
            postCss: [
                require('postcss-font-magician'),
                require('css-mqpacker')({
                    sort: true,
                }),
                //require('postcss-remove-root'),
                require('postcss-url')({}),
                // require('postcss-custom-properties')({
                //     preserve: false
                // }),
            ],
            processCssUrls: true,
            //extractVueStyles: 'css/vue.css',
        })
            .sourceMaps();
    },
    buildSassProduction(from, to) {
        const self = this;
        self.mix.sass(from, to).options({
            autoprefixer: {
                options: {
                    browsers: [
                        'last 6 versions',
                    ]
                }
            },
            postCss: [
                require('cssnano'),
                require('postcss-font-magician'),
                require('css-mqpacker')({
                    sort: true,
                }),
                //require('postcss-remove-root'),
            ],
            processCssUrls: true,
            clearConsole: true,
        }).version();
    },
};
if (process.env.section && process.env.section === 'app') {
    if (!mix.inProduction()) {
        MIX.run(mix).app().development(process.env.APP_URL);
    } else {
        MIX.run(mix).app().production();
    }
}
if (process.env.section && process.env.section === 'cmf') {
    if (!mix.inProduction()) {
        MIX.run(mix).cmf().development(process.env.CMF_URL);
    } else {
        MIX.run(mix).cmf().production();
    }
}
