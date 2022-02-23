const mix = require('laravel-mix');

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


const SpeedMeasurePlugin = require('speed-measure-webpack-plugin')

mix.js('resources/js/app.js', 'public/js')
    .vue({ version: 2 })
    .postCss('resources/css/app.css','public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig({
        plugins: [
          new SpeedMeasurePlugin({
            disable: !process.env.MEASURE,
          }),
        ],
      });
    
