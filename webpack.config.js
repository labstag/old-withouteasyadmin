const path = require('path');
var Encore = require('@symfony/webpack-encore');
// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  // directory where compiled assets will be stored
  .setOutputPath('apps/public/assets/')
  // public path used by the web server to access the output path
  .setPublicPath('/assets')
  // only needed for CDN's or sub-directory deploy
  .setManifestKeyPrefix('assets/')
  .copyFiles([
    {
      from: './node_modules/tarteaucitronjs',
      to: 'tarteaucitron/[path][name].[ext]',
      pattern: /\.(js)$/,
      includeSubdirectories: false
    },
    {
      from: './node_modules/tarteaucitronjs/css',
      to: 'tarteaucitron/css/[path][name].[ext]'
    },
    {
      from: './node_modules/tarteaucitronjs/lang',
      to: 'tarteaucitron/lang/[path][name].[ext]'
    },
    {
      from: './node_modules/@ckeditor/ckeditor5-build-classic/build',
      to: 'ckeditor/[path][name].[ext]'
    }
  ])
  /*
   * ENTRY CONFIG
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
   */
  .addEntry('front', './assets/js/front.js')
  .addEntry('back', './assets/js/back.js')

  // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
  // .enableStimulusBridge('./assets/controllers.json')

  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  .enableSingleRuntimeChunk()
  // .disableSingleRuntimeChunk()

  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(true)

  // enables @babel/preset-env polyfills
  // .configureBabelPresetEnv((config) => {
  //   config.useBuiltIns = 'usage';
  //   config.corejs = 3;
  // })

  // enables Sass/SCSS support
  .enableSassLoader()
  .enablePostCssLoader(
    (options) => {
      options.postcssOptions = {
        path: path.resolve(__dirname, 'postcss.config.js')
      };
    }
  )

  // uncomment if you use TypeScript
  //.enableTypeScriptLoader()

  // uncomment if you use React
  //.enableReactPreset()

  // uncomment to get integrity="..." attributes on your script & link tags
  // requires WebpackEncoreBundle 1.4 or higher
  .enableIntegrityHashes(true)

  // uncomment if you're having problems with a jQuery plugin
  // .autoProvidejQuery()
  .configureDevServerOptions(options => {
    options.allowedHosts = 'all';
  })

  .addAliases({
    '@nm': path.resolve(__dirname, 'node_modules'),
    '@': path.resolve(__dirname, 'assets'),
    '@scss': path.resolve(__dirname, 'assets/scss')
  })
;

module.exports = Encore.getWebpackConfig();