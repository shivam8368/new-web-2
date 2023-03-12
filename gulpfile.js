'use strict';
const { src, dest, watch, series, parallel  } = require('gulp');
const concat = require('gulp-concat');
const fileinclude = require('gulp-file-include');
const uglify = require('gulp-uglify-es').default;
const svgmin = require('gulp-svgmin');
const file2json = require('gulp-file-contents-to-json');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const ts = require('gulp-typescript');
const fs = require('fs');
const merge = require('merge-stream');
const replace = require('gulp-replace');

const nodepath = 'node_modules/';


async function cleanAssets(done)
{
    let del = await import('del');
    del.deleteSync(['public/assets/**']);

    return done();
}

function js_front()
{
    return src([

        // Custom Libs
        nodepath + 'jquery/dist/jquery.min.js',
        nodepath + 'toastr/build/toastr.min.js',
        nodepath + 'sweetalert2/dist/sweetalert2.js',

        // Plugins
        'resources/assets/front/js/plugins/*.js',

        // Componenets
        'resources/assets/front/js/components/*.js',

        // Pages
        'resources/assets/front/js/pages/*.js',

        // Custom Libs
        'resources/assets/libs/custom-icons/dist/icons.js',
        'resources/assets/libs/right-panels/right-panels.js',

        // Base
        'resources/assets/front/js/app.js'
    ])
        .pipe(concat('front-js.js'))
        .pipe(uglify())
        .pipe(dest('public/assets/front'));
}

function css_front()
{
    let basic = src([
        'resources/assets/front/css/reset.css',

        // node_modules libs
        nodepath + 'toastr/build/toastr.css',
        nodepath + 'sweetalert2/dist/sweetalert2.css',

        // Custom libs
        'resources/assets/libs/right-panels/right-panels.css',

        // Plugins
        'resources/assets/front/js/plugins/*.css',

        // Pages
        'resources/assets/front/css/pages/**/*.css',

        // Components
        'resources/assets/front/css/components/**/*.css',

        // Base
        'resources/assets/front/css/app.css',
    ])
        .pipe(concat('front-css.css'))
        .pipe(autoprefixer('last 2 versions'))
        .pipe(cleanCSS())
        .pipe(dest('public/assets/front'));


    return basic;
}

function ts_front()
{
    let tsconfig = JSON.parse(fs.readFileSync('resources/assets/front/ts/tsconfig.json').toString());
    tsconfig.compilerOptions.outFile = 'front-ts.js';

    let gulpSource = [
        'resources/assets/front/ts/**/!(bootstrap).ts',
        'resources/assets/front/ts/bootstrap.ts'
    ];

    return src(gulpSource)
        .pipe(ts(tsconfig.compilerOptions))
        .pipe(uglify())
        .pipe(dest('public/assets/front'));
}

function img_front()
{
    return src('resources/assets/front/img/**/*.*')
        .pipe(dest('public/assets/front/img'));
}

function libs_icons_minify_icons()
{
    // Minify svg icons
    return src('resources/assets/libs/custom-icons/src/icons/**/*.svg')
        .pipe(svgmin({
            full: true,
            plugins: [
                'removeDoctype',
                'removeXMLProcInst',
                'removeComments',
                'removeMetadata',
                'removeEditorsNSData',
                'cleanupAttrs',
                'mergeStyles',
                'inlineStyles',
                'minifyStyles',
                'cleanupIDs',
                'removeUselessDefs',
                'cleanupNumericValues',
                'convertColors',
                'removeUnknownsAndDefaults',
                'removeNonInheritableGroupAttrs',
                'removeUselessStrokeAndFill',
                'cleanupEnableBackground',
                'removeHiddenElems',
                'removeEmptyText',
                'convertShapeToPath',
                'convertEllipseToCircle',
                'moveElemsAttrsToGroup',
                'moveGroupAttrsToElems',
                'collapseGroups',
                'convertPathData',
                'convertTransform',
                'removeEmptyAttrs',
                'removeEmptyContainers',
                'mergePaths',
                'removeUnusedNS',
                'sortDefsChildren',
                'removeTitle',
                'removeDesc'
            ]
        }))
        .pipe(dest('resources/assets/libs/custom-icons/dist/icons'));
}

function libs_icons_json_map()
{
    // Create icons.json map
    return src('resources/assets/libs/custom-icons/dist/icons/**/*.svg')
        .pipe(file2json('icons.json', {
            extname: false,
            flatpathdelimiter: '/',
            strip: /\//,
        }))
        .pipe(dest('resources/assets/libs/custom-icons/dist'));
}

function libs_icons_generate_js()
{
    // Generate icons dist JS
    return src([
        'resources/assets/libs/custom-icons/src/icons.js',
    ])
        .pipe(fileinclude({
            prefix: '@@',
            basepath: '@file'
        }))
        .pipe(uglify())
        .pipe(dest('resources/assets/libs/custom-icons/dist')).on('end', async function(){
            let del = await import('del');
            del.deleteSync('resources/assets/libs/custom-icons/dist/icons.json');
            del.deleteSync('resources/assets/libs/custom-icons/dist/icons')
        });
}

function layout_files_version()
{
    let version = (new Date().getTime());

    return src([
        'resources/views/**/*.blade.php',
    ], {base: './'})
        .pipe(replace(/\.(css|js)\?\d+/g, '.$1?'+version))
        .pipe(dest('./'));
}

function concat_front()
{
    let concat_css = src([
        'public/assets/front/front-css.css'
    ])
        .pipe(concat('front.css'))
        .pipe(dest('public/assets/front'))
        .on('end', async function() {
            let del = await import('del');
            del.deleteSync('public/assets/front/front-css.css');
        });


    let concat_js = src([
        'public/assets/front/front-js.js',
        'public/assets/front/front-ts.js'
    ])
        .pipe(concat('front.js'))
        .pipe(dest('public/assets/front'))
        .on('end', async function() {
            let del = await import('del');
            del.deleteSync('public/assets/front/front-js.js');
            del.deleteSync('public/assets/front/front-ts.js');
        });

    return merge(concat_css, concat_js);
}


exports.compile = series(
    cleanAssets,

    // Custom libs
    series(libs_icons_minify_icons, libs_icons_json_map, libs_icons_generate_js),

    parallel(
        js_front,
        css_front,
        ts_front,
        img_front
    ),

    concat_front,
    layout_files_version
);
