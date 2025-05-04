const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/')
    .setPublicPath('/bundles/markocupiccontaofrontendusernotification')
    .setManifestKeyPrefix('')

    .addEntry('js/frontend_user_notification.built', './assets/js/frontend_user_notification.built.js')

    // Typescripts
    //.addEntry('js/my_typescript', './assets/ts/my_typescript.ts')
    //.enableTypeScriptLoader()

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps()
    .enableVersioning()
    .enableVueLoader()

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .enablePostCssLoader()

;

module.exports = Encore.getWebpackConfig();;
