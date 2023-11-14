import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // css
                './node_modules/admin-lte/build/less/AdminLTE.less',
                './resources/assets/less/app.less',
                './resources/assets/less/overrides.less',
                "./node_modules/bootstrap/dist/css/bootstrap.css",
                "./node_modules/@fortawesome/fontawesome-free/css/all.css",
                "./public/css/build/AdminLTE.css",
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
