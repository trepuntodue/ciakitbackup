const mix = require("laravel-mix");

if (mix == "undefined") {
  const { mix } = require("laravel-mix");
}

require("laravel-mix-merge-manifest");

if (mix.inProduction()) {
  var publicPath = "publishable/assets";
} else {
  var publicPath = "../../../public/vendor/webkul/ui/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.inProduction();

mix
  .js(
    [__dirname + "/src/Resources/assets/js/app.js", __dirname + "/src/Resources/assets/js/dropdown.js", __dirname + "../../../PSW/Cinema/src/Resources/assets/js/custom.js"],
    "js/ui.js" // PWS#13-comp
  )
  .copy(__dirname + "/src/Resources/assets/images", publicPath + "/images")
  .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/ui.css")
  .options({
    processCssUrls: false,
  });

if (!mix.inProduction()) {
  mix.sourceMaps();
}

if (mix.inProduction()) {
  mix.version();
}
