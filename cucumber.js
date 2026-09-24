// Default cucumber-js config - runs the Drupal Standard suite.
//
// The View Modes Inventory feature set is split by Drupal flavour:
//   tests/features/drupal/     - Drupal Standard profile + Drupal Canvas
//   tests/features/drupalcms/  - Drupal CMS distribution + Drupal Canvas
//
// This config loads only the `drupal/` features. The Drupal CMS suite lives in
// `cucumber.drupalcms.js`:
//
//   npx cucumber-js --config cucumber.js             # Drupal Standard (default)
//   npx cucumber-js --config cucumber.drupalcms.js   # Drupal CMS

const baseWorldParameters = require('./cucumber.shared.js');

module.exports = {
  default: {
    timeout: 60000,
    retry: 1,
    requireModule: ['tsx/cjs'],
    require: ['node_modules/@vardot/varbase-e2e/tests/step-definitions/**/*.js'],
    paths: ['tests/features/drupal/**/*.feature'],
    format: [
      '@cucumber/pretty-formatter',
      'json:tests/reports/drupal/cucumber_report.json',
    ],
    worldParameters: {
      ...baseWorldParameters,
      screenshot: {
        ...baseWorldParameters.screenshot,
        dir: './tests/screenshots/drupal',
      },
      video: { ...baseWorldParameters.video, dir: './tests/videos/drupal' },
    },
  },
};
