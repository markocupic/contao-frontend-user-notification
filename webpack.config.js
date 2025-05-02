const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/')
    .setPublicPath('/bundles/markocupiccontaofrontendusernotification')
    .setManifestKeyPrefix('')

    //.addEntry('backend', './assets/backend.js') // Register Stimulus controllers

    .copyFiles({
        from: './assets/js',
        to: 'js/[path][name].[hash:8].[ext]',
        pattern: /(FrontendUserNotification\.js|)$/,
    })

    // Typescripts
    //.addEntry('js/my_typescript', './assets/ts/my_typescript.ts')
    //.enableTypeScriptLoader()

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps()
    .enableVersioning()

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
