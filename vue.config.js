// const path = require('path')

// module.exports = {
//   publicPath: '../',
//   configureWebpack: {
//     output: {
//       path: path.resolve(__dirname, 'dist'),
//       filename: "../dist/js/app.js",
//       chunkFilename: '../dist/js/[id].chunk.js',

//     },
//     devtool: 'source-map'

//   },
//   chainWebpack: config => {
//     config.optimization.delete("splitChunks");
//   },
//   css: {
//     extract: {
//       path: path.resolve(__dirname, 'dist'),
//       filename: "../dist/css/app.css",
//       chunkFilename: '../dist/css/chunk.[id].css',
//     },
//   }
// }

const path = require('path')

module.exports = {
  publicPath: '/dist/',
  configureWebpack: {
    output: {
      filename: "./js/app.js",
      chunkFilename: './js/[id].chunk.js',

    },
    devtool: 'source-map'

  },
  chainWebpack: config => {
    config.optimization.delete("splitChunks");
  },
  css: {
    extract: {
      path: path.resolve(__dirname, 'dist'),
      filename: "./css/app.css",
      chunkFilename: './css/chunk.[id].css',
    },
  }
}