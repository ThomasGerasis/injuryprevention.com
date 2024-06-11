const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  context: path.resolve(__dirname, "assets"),
  entry: {
    main: "./js/main.js",
    customSwiper: "./js/customSwiper.js",
    bootstrap: "./js/bootstrap.js",
    footer: "./js/footer.js",
    articleSlider: "./js/shortcodes/articleSlider.js",
    articleCategorySlider: "./js/shortcodes/articleCategorySlider.js",
    searchArticles: "./js/shortcodes/searchArticles.js",
    faqs: "./js/faqs.js",
    articleRow: "./js/articleRow.js",
    contactForm: "./js/shortcodes/contactForm.js",
    pagination: "./js/components/pagination.js",
    timeline: "./js/timeline.js",
    charts: "./js/components/charts.js",
  },
  output: {
    filename: "js/[name].js",
    path: path.resolve(__dirname, "public/dist"),
    publicPath: "/dist/",
    clean: true
  },
  resolve: {
    extensions: [".ts", ".js"],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "css/[name].css",
    }),
  ],
  watchOptions: {
    aggregateTimeout: 200,
    poll: 1000,
    ignored: /node_modules/,
    followSymlinks: true
  },
  module: {
    rules: [
      {
        test: /\.ts$/,
        use: "ts-loader",
        exclude: [/node_modules/],
      },
      {
        test: /\.s[ac]ss$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              importLoaders: 1,
            },
          },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                plugins: ["autoprefixer"],
              },
            },
          },
          {
            loader: "sass-loader",
            options: {
              implementation: require("sass"),
              sourceMap: true,
            },
          },
        ],
      },
      {
        test: /\.(png|svg|jpg|jpeg|gif)$/i,
        type: "asset/resource",
      },
    ],
  },
};
