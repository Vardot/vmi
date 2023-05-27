const path = require('path');
const isDev = (process.env.NODE_ENV !== 'production');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const autoprefixer = require('autoprefixer');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const postcssRTLCSS = require('postcss-rtlcss');

module.exports = {
  mode: 'production',
  entry: {
    // ################################################
    // SCSS
    // ################################################
    // Admin
    "vmi.admin.theme": ["./scss/vmi.admin.theme.scss"],
    // Layout
    "hero/layout/hero-xlarge": ["./scss/hero/layout/hero-xlarge.scss"],
    "horizontal-media-teaser/layout/horizontal-media-teaser": ["./scss/horizontal-media-teaser/layout/horizontal-media-teaser.scss"],
    "text-teaser/layout/text-teaser": ["./scss/text-teaser/layout/text-teaser.scss"],
    "tout/layout/tout": ["./scss/tout/layout/tout.scss"],
    "tout/layout/tout-medium": ["./scss/tout/layout/tout-medium.scss"],
    "tout/layout/tout-large": ["./scss/tout/layout/tout-large.scss"],
    "tout/layout/tout-xlarge": ["./scss/tout/layout/tout-xlarge.scss"],
    "vertical-media-teaser/layout/vertical-media-teaser": ["./scss/vertical-media-teaser/layout/vertical-media-teaser.scss"],
    // Theme
    "hero/theme/hero-xlarge": ["./scss/hero/theme/hero-xlarge.scss"],
    "horizontal-media-teaser/theme/horizontal-media-teaser": ["./scss/horizontal-media-teaser/theme/horizontal-media-teaser.scss"],
    "horizontal-media-teaser/theme/horizontal-media-teaser-xsmall": ["./scss/horizontal-media-teaser/theme/horizontal-media-teaser-xsmall.scss"],
    "horizontal-media-teaser/theme/horizontal-media-teaser-small": ["./scss/horizontal-media-teaser/theme/horizontal-media-teaser-small.scss"],
    "horizontal-media-teaser/theme/horizontal-media-teaser-medium": ["./scss/horizontal-media-teaser/theme/horizontal-media-teaser-medium.scss"],
    "horizontal-media-teaser/theme/horizontal-media-teaser-large": ["./scss/horizontal-media-teaser/theme/horizontal-media-teaser-large.scss"],
    "horizontal-media-teaser/theme/horizontal-media-teaser-xlarge": ["./scss/horizontal-media-teaser/theme/horizontal-media-teaser-xlarge.scss"],
    "text-teaser/theme/text-teaser": ["./scss/text-teaser/theme/text-teaser.scss"],
    "text-teaser/theme/text-teaser-small": ["./scss/text-teaser/theme/text-teaser-small.scss"],
    "text-teaser/theme/text-teaser-medium": ["./scss/text-teaser/theme/text-teaser-medium.scss"],
    "text-teaser/theme/text-teaser-large": ["./scss/text-teaser/theme/text-teaser-large.scss"],
    "tout/theme/tout": ["./scss/tout/theme/tout.scss"],
    "tout/theme/tout-medium": ["./scss/tout/theme/tout-medium.scss"],
    "tout/theme/tout-large": ["./scss/tout/theme/tout-large.scss"],
    "tout/theme/tout-xlarge": ["./scss/tout/theme/tout-xlarge.scss"],
    "vertical-media-teaser/theme/vertical-media-teaser": ["./scss/vertical-media-teaser/theme/vertical-media-teaser.scss"],
    "vertical-media-teaser/theme/vertical-media-teaser-xsmall": ["./scss/vertical-media-teaser/theme/vertical-media-teaser-xsmall.scss"],
    "vertical-media-teaser/theme/vertical-media-teaser-small": ["./scss/vertical-media-teaser/theme/vertical-media-teaser-small.scss"],
    "vertical-media-teaser/theme/vertical-media-teaser-medium": ["./scss/vertical-media-teaser/theme/vertical-media-teaser-medium.scss"],
    "vertical-media-teaser/theme/vertical-media-teaser-large": ["./scss/vertical-media-teaser/theme/vertical-media-teaser-large.scss"],
    "vertical-media-teaser/theme/vertical-media-teaser-xlarge": ["./scss/vertical-media-teaser/theme/vertical-media-teaser-xlarge.scss"],
  },
  output: {
    path: path.resolve(__dirname, 'css'),
    pathinfo: true,
    publicPath: '',
  },
  module: {
    rules: [
      {
        test: /\.(png|jpe?g|gif|svg)$/,
        exclude: /sprite\.svg$/,
        type: 'javascript/auto',
        use: [{
            loader: 'file-loader',
            options: {
              name: '[path][name].[ext]', //?[contenthash]
              outputPath: '../../'
            },
          },
          {
            loader: 'img-loader',
            options: {
              enabled: !isDev,
            },
          },
        ],
      },
      {
        test: /\.(css|scss)$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              name: '[name].[ext]?[hash]',
            }
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: isDev,
              importLoaders: 2,
              url: (url) => {
                // Don't handle sprite svg
                if (url.includes('sprite.svg')) {
                  return false;
                }

                return true;
              },
            },
          },
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: isDev,
              postcssOptions: {
                plugins: [
                  autoprefixer(),
                  postcssRTLCSS(),
                  ['postcss-perfectionist', {
                    format: 'expanded',
                    indentSize: 2,
                    trimLeadingZero: true,
                    zeroLengthNoUnit: false,
                    maxAtRuleLength: false,
                    maxSelectorLength: false,
                    maxValueLength: false,
                  }]
                ],
              },
            },
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: isDev,
              // Global SCSS imports:
              additionalData: `
                @use "sass:color";
                @use "sass:math";
              `,
            },
          },
        ],
      },
    ],
  },
  resolve: {
    modules: [
      path.join(__dirname, 'node_modules'),
    ],
    extensions: ['.js', '.json'],
  },
  plugins: [
    new RemoveEmptyScriptsPlugin(),
    new CleanWebpackPlugin({
      cleanStaleWebpackAssets: false
    }),
    new MiniCssExtractPlugin(),
  ],
  watchOptions: {
    aggregateTimeout: 300,
    ignored: ['**/*.woff', '**/*.json', '**/*.woff2', '**/*.jpg', '**/*.png', '**/*.svg', 'node_modules', 'images'],
  }
};
