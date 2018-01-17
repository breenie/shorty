const path              = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const HtmlWebpackPlugin = require('html-webpack-plugin');
const UglifyJSPlugin    = require('uglifyjs-webpack-plugin');


module.exports = {
  entry:   './app/app.js',
  output:  {
    filename: 'bundle.js',
    path:     path.resolve(__dirname, 'dist')
  },
  module:  {
    rules: [
      {
        test: /\.css$/,
        use:  ExtractTextPlugin.extract({
          fallback: "style-loader",
          use:      "css-loader"
        })
      },
      {
        test: /\.(png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/,
        use:  [
          'file-loader'
        ]
      },
      {
        test: /\.html$/,
        use:  [{
          loader:  'html-loader',
          options: {
            minimize: true
          }
        }]
      },
      {
        test:   /\.ico$/i,
        loader: 'file-loader?name=[name].[ext]'
      },
      {
        test: /\.js$/,
        use:  {
          loader: 'babel-loader',
          options: {
            presets: ['env']
          }
        }
      }
    ]
  },
  plugins: [
    new ExtractTextPlugin("styles.css"),
    new HtmlWebpackPlugin({
      favicon:  './app/favicon.ico',
      template: './app/index.html',
    }),
    new UglifyJSPlugin()
  ]
};