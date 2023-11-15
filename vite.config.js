import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';
export default defineConfig({
    plugins: [
        laravel({
            less: true,
            input: [
                // css
                './resources/assets/less/overrides.less',
                './resources/assets/less/app.less',
                // "./node_modules/bootstrap/dist/css/bootstrap.css",
                "./node_modules/@fortawesome/fontawesome-free/css/all.css",
                // "./public/css/build/AdminLTE.css",
                "./node_modules/jquery-ui-bundle/jquery-ui.css",
                "./node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css",
                "./node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css",
                "./node_modules/blueimp-file-upload/css/jquery.fileupload.css",
                "./node_modules/blueimp-file-upload/css/jquery.fileupload-ui.css",
                "./node_modules/ekko-lightbox/dist/ekko-lightbox.css",
                "./node_modules/bootstrap-table/dist/bootstrap-table.css",
                "./public/css/build/app.css",
                "./public/css/build/all.css",
                "./node_modules/select2/dist/css/select2.css",
                "./public/css/build/overrides.css",
                // js
                "./resources/assets/js/vue.js", // require()s vue, and require()s bootstrap.js
                "./resources/assets/js/snipeit.js", //this is the actual Snipe-IT JS
                "./resources/assets/js/snipeit_modals.js",
                './resources/assets/js/app.js'
            //
                // lots of node_modules here - should this be subsumed by require()?
                // "./node_modules/jquery/dist/jquery.js",
                // "./node_modules/select2/dist/js/select2.full.min.js",
                // "./node_modules/admin-lte/dist/js/adminlte.min.js",
                // "./node_modules/tether/dist/js/tether.js",
                // "./node_modules/jquery-ui-bundle/jquery-ui.js",
                // "./node_modules/jquery-slimscroll/jquery.slimscroll.js",
                // "./node_modules/jquery.iframe-transport/jquery.iframe-transport.js",
                // "./node_modules/blueimp-file-upload/js/jquery.fileupload.js",
                // "./node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js",
                // "./node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js",
                // "./node_modules/ekko-lightbox/dist/ekko-lightbox.js",
                // "./resources/assets/js/extensions/pGenerator.jquery.js",
                // "./node_modules/chart.js/dist/Chart.js",
                // "./resources/assets/js/signature_pad.js",
                // //"./node_modules/jquery-form-validator/form-validator/jquery.form-validator.js", //problem?
                // "./node_modules/list.js/dist/list.js",
                // "./node_modules/clipboard/dist/clipboard.js",
            ],
            detectTls: true,
            refresh: true,

        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
