# View Modes Inventory - Varbase functional testing suite

Automated functional acceptance tests for the View Modes Inventory module,
written in Gherkin and driven through a real browser by
[@vardot/varbase-e2e](https://www.npmjs.com/package/@vardot/varbase-e2e)
(Playwright + Cucumber-js), plus PHPUnit unit tests for the hook contract.

## Layout

| Path | Runs on | Covers |
| --- | --- | --- |
| `features/drupal/` | Drupal Standard profile + Drupal Canvas | The VMI view modes are listed for a content type, can be enabled and disabled, and the full content display opens its Drupal Canvas template. |
| `features/drupalcms/` | Drupal CMS + Drupal Canvas | The node template tab points at the full content display and the node default display stays editable, which is what the Drupal CMS helper would otherwise take over. |
| `src/Unit/` | No site | The hook registration and the ordering around the Drupal CMS helper. |

`cucumber.js` loads the `drupal` features, `cucumber.drupalcms.js` loads the
`drupalcms` ones, and both share `cucumber.shared.js`.

## The site the suite expects

Drupal 11.4 with `canvas`, `canvas_override` and `vmi` enabled, plus
`drupal_cms_helper` on the Drupal CMS side, and the
`tests/fixtures/vmi_test_base` recipe applied. That recipe creates the
`vmi_test` content type whose full view mode is a Drupal Canvas content
template, and the `/vmi-test-node` node the suite drives.

```bash
ddev composer require drupal/canvas_override
ddev drush pm:enable canvas canvas_override vmi -y
ddev drush recipe web/modules/contrib/vmi/tests/fixtures/vmi_test_base
ddev drush cache:rebuild
```

The suite logs in as `webmaster` / `dD.123123ddd`. Override with
`DRUPAL_ADMIN_USERNAME` and `DRUPAL_ADMIN_PASSWORD`.

## Running it

```bash
yarn install
./node_modules/.bin/playwright install --with-deps chromium

LAUNCH_URL=https://my-site.ddev.site yarn test:drupal
LAUNCH_URL=https://my-drupal-cms-site.ddev.site yarn test:drupalcms
```

Other commands:

```bash
yarn test:dry-run        # step phrasings only, no browser and no site
yarn test:headed         # watch it drive the browser
yarn generate-reports    # HTML + PDF from tests/reports/
```

The unit tests need no site:

```bash
cd web/core && ../../vendor/bin/phpunit --group vmi ../modules/contrib/vmi/tests/src/Unit
```

`.gitlab-ci.yml` runs all three layers on every pipeline.
