// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  // This option lets define header global. Default path load from public folder
  app: {
    head: {
      title: "NUXT JS 3 FE",
      charset: "utf-8",
      viewport: "width=device-width, initial-scale=1",
      meta: [{ name: "description", content: "My amazing site." }],
      link: [
        {
          rel: "icon",
          type: "image/x-icon",
          href: "/favicon.ico",
        },
        // Bootstrap 5 css
        // {
        //   rel: "stylesheet",
        //   type: "text/css",
        //   href: "/css/bootstrap.min.css"
        // },
      ],
      script: [
        // Bootstrap 5 js
        // {
        //   type: "text/javascript",
        //   src: "/js/bootstrap.bundle.min.js",
        //   body: true
        // },
      ],
    },
  },
  // Global CSS
  css: [
    "~/assets/css/common.css",
    "~/assets/scss/main.scss",
    "~/assets/font-awesome-6/css/all.css",
  ],
  // Load module for project
  modules: ["@pinia/nuxt", "@nuxtjs/i18n", "nuxt-lodash"],
  // Plugin run before rendering page
  plugins: [
    { src: "~/plugins/i18n.config.ts", mode: 'client' },
    // { src: "~/plugins/vee-validate.config.ts", mode: 'client' }
  ],
  i18n: {
    vueI18n: "./plugins/i18n.config.ts",
  },
  lodash: {
    prefix: "_",
    prefixSkip: ["string"],
    upperAfterPrefix: false,
    exclude: ["map"],
    alias: [
      ["camelCase", "stringToCamelCase"], // => stringToCamelCase
      ["kebabCase", "stringToKebab"], // => stringToKebab
      ["isDate", "isLodashDate"], // => _isLodashDate
    ],
  },
  build: {
    // transpile: ['@fortawesome/vue-fontawesome'],
  },
});
