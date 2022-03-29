const mix = require('laravel-mix');

// /*
//  |--------------------------------------------------------------------------
//  | Mix Asset Management
//  |--------------------------------------------------------------------------
//  |
//  | Mix provides a clean, fluent API for defining some Webpack build steps
//  | for your Laravel application. By default, we are compiling the Sass
//  | file for the application as well as bundling up all the JS files.
//  |
//  */

// mix.ts('resources/js/app.ts', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');


mix.ts('resources/ts/app.ts', 'public/js').vue()
  .sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({
  resolve: {
    extensions: [".js", ".jsx", ".vue", ".ts", ".tsx"],
    alias: {
      'vue$': "vue/dist/vue.esm.js"
    }
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        loader: "ts-loader",
        // options: { appendTsSuffixTo: [/\.vue$/] },
        options: {
          appendTsSuffixTo: [/\.vue$/] //①optionsを追加
        },
        exclude: /node_modules/
      }
    ]
  }
});
const GoogleFontsPlugin = require("google-fonts-webpack-plugin")

module.exports = {
    "entry": "index.js",
    /* ... */
    plugins: [
        new GoogleFontsPlugin({
            fonts: [
                { family: "M PLUS Rounded 1c" }
            ]
        })
    ]
}
