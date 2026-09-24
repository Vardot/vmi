// cucumber-js config for the Drupal CMS suite.
//
// Loads only the `tests/features/drupalcms/` features and routes reports /
// screenshots / videos into Drupal-CMS-specific subdirectories so they do not
// collide with the Drupal Standard run.
//
//   npx cucumber-js --config cucumber.drupalcms.js

const baseWorldParameters = require('./cucumber.shared.js');

module.exports = {
  default: {
    timeout: 60000,
    retry: 1,
    requireModule: ['tsx/cjs'],
    require: ['node_modules/@vardot/varbase-e2e/tests/step-definitions/**/*.js'],
    paths: ['tests/features/drupalcms/**/*.feature'],
    format: [
      '@cucumber/pretty-formatter',
      'json:tests/reports/drupalcms/cucumber_report.json',
    ],
    worldParameters: {
      ...baseWorldParameters,
      screenshot: {
        ...baseWorldParameters.screenshot,
        dir: './tests/screenshots/drupalcms',
      },
      video: { ...baseWorldParameters.video, dir: './tests/videos/drupalcms' },
    },
  },
};
