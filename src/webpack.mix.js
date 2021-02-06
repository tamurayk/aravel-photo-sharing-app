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

// note: mix.ts
//  See: node_modules/laravel-mix/src/components/TypeScript.js
mix.ts('resources/ts/app.tsx', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   // note:
   //  - Laravel Mix = webpack の設定Wrapper
   //  - 内部で事前設定済みの `webpack.config.js` をロードしている
   //    - `node_modules/laravel-mix/setup/webpack.config.js`
   //  - `.webpackConfig` を設定する事で、上記の設定をカスタマイズできる
   .webpackConfig({
     // 各設定の意味はwebpack のドキュメントを参照
     // See: https://webpack.js.org/configuration/
     module: {
       // configuration regarding modules
       rules: [
         {
           test: /\.tsx?$/,
           loader: 'ts-loader',
           exclude: /node_modules/,
         },
       ],
     },
     resolve: {
       // directories where to look for modules (in order)
       // note: extensions
       //   resolve.extensionsに拡張子を登録することで、TypeScript内のimport文で拡張子を省略できる
       extensions: ['*', '.js', '.jsx', '.ts', '.tsx'],
     },
   })
   .version();

// 本番環境でのみ
if (mix.inProduction()) {
   // note: version
   //   - what
   //     - コンパイル済みファイルのファイル名に一意のハッシュを自動的に付け加えます
   //   - why
   //     - 古いコードがキャッシュされる事への対策
    mix.version();
}
