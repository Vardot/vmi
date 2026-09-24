// Shared cucumber-js worldParameters for both Drupal flavours.
//
// `cucumber.js` (Drupal Standard) and `cucumber.drupalcms.js` (Drupal CMS)
// import this and layer their own report / screenshot / video paths on top so
// artifacts from the two suites do not collide.

module.exports = {
  launchUrl: process.env.LAUNCH_URL || 'http://localhost',
  users: {
    webmaster: {
      username: process.env.DRUPAL_ADMIN_USERNAME || 'webmaster',
      email: 'webmaster@example.test',
      password: process.env.DRUPAL_ADMIN_PASSWORD || 'dD.123123ddd',
      isAdmin: true,
    },
  },
  // The content type and node the tests/fixtures/vmi_test_base recipe creates.
  // Held here so the fixture is defined in one place.
  vmi: {
    bundle: process.env.VMI_TEST_BUNDLE || 'vmi_test',
    bundleLabel: process.env.VMI_TEST_BUNDLE_LABEL || 'VMI test',
    nodeAlias: process.env.VMI_TEST_NODE_ALIAS || '/vmi-test-node',
    nodeTitle: process.env.VMI_TEST_NODE_TITLE || 'VMI test node',
  },
  minWaitTime: {
    page: 8000,
    before_scenario: 0,
    after_scenario: 0,
    before_step: 0,
    after_step: 0,
  },
  selectors: {
    css: {},
    xpath: {},
    filesPath: './tests/selectors/',
    files: [],
    offset: 60,
    breakpoints: {
      xs: { width: 375, height: 667 },
      sm: { width: 576, height: 800 },
      md: { width: 768, height: 1024 },
      lg: { width: 992, height: 768 },
      xl: { width: 1200, height: 900, default: true },
      xxl: { width: 1400, height: 900 },
    },
  },
  screenshot: {
    dir: './tests/screenshots',
    purge: false,
    onFailed: true,
    onEveryStep: false,
    alwaysFullscreen: false,
    failedPrefix: 'failed_',
    filenamePattern: '{datetime}.{feature_file}.feature_{step_line}.{ext}',
    filenamePatternFailed:
      '{failed_prefix}{datetime}.{feature_file}.feature_{step_line}.{ext}',
    infoTypes: '',
  },
  video: {
    mode: process.env.VARBASE_E2E_VIDEO || 'on-failure',
    dir: './tests/videos',
    size: { width: 1280, height: 720 },
    filenamePattern: '{datetime}.{feature_file}.{scenario}.{status}.{ext}',
  },
  javascript: {
    mode: process.env.VARBASE_E2E_JS_ERROR_MODE || 'warn',
    levels: ['error'],
    ignore: '',
    beforeScenario: false,
    afterScenario: true,
  },
};
