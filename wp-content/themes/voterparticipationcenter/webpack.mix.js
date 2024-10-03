const mix = require("laravel-mix");

mix
  .js(
    [
      "assets/js/app.js",
      "assets/js/blog.js",
      "assets/js/research.js",
      "assets/js/voter-info.js",
    ],
    "./app.js"
  )
  .vue({ version: 2 })
  .sass("assets/sass/style.scss", "./")
  .options({
    processCssUrls: false,
  })
  .setPublicPath("./")
  .setResourceRoot("./")
  .version();
