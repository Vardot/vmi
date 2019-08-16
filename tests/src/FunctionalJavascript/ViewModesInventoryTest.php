<?php

namespace Drupal\Tests\vmi\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Tests View Modes Inventory.
 *
 * @group vmi
 */
class ViewModesInventoryTest extends WebDriverTestBase {

  use StringTranslationTrait;

  /**
   * Image content type.
   *
   * @var \Drupal\node\Entity\NodeType
   */
  protected $imageContentType;

  /**
   * Video content type.
   *
   * @var \Drupal\node\Entity\NodeType
   */
  protected $videoContentType;

  /**
   * Media content type.
   *
   * @var \Drupal\node\Entity\NodeType
   */
  protected $mediaContentType;

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'user',
    'filter',
    'toolbar',
    'block',
    'views',
    'node',
    'field',
    'field_ui',
    'layout_discovery',
    'ds',
    'ds_extras',
    'field_group',
    'smart_trim',
    'media',
    'vmi',
  ];

  /**
   * A user with the field display permissions.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $webUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    \Drupal::service('theme_installer')->install(['seven']);

    \Drupal::service('config.factory')->getEditable('system.theme')
      ->set('admin', 'seven')
      ->save();

    $permissions = [
      'access toolbar',
      'view the administration theme',
      'administer node fields',
      'administer node display',
      'admin display suite',
      'admin classes',
      'admin fields',
    ];

    $this->webUser = $this->drupalCreateUser($permissions);
    $this->drupalLogin($this->webUser);

    // Create Image Content type with Main Image field.
    $this->createImageContentType();

    // Create Video Content type with Main Video field.
    $this->createVideoContentType();

    // Create Media Content type with Main Media field.
    $this->createMediaContentType();
  }

  /**
   * Test Display Image Content Type.
   */
  public function testDisplayImageContentType() {
    $this->drupalGet('admin/structure/types/manage/image_content/display');
    $custom_display_settings_text = $this->t('Custom display settings');
    $this->assertSession()->pageTextContains($custom_display_settings_text);
    $this->clickLink($custom_display_settings_text);

    $this->assertSession()->pageTextContains($this->t('Use custom display settings for the following view modes'));

    // Make sure that we do have all VMI custom display view modes ready to use.
    $this->assertSession()->pageTextContains($this->t('Hero - xlarge'));

    $this->assertSession()->pageTextContains($this->t('Tout - large'));
    $this->assertSession()->pageTextContains($this->t('Tout - medium'));
    $this->assertSession()->pageTextContains($this->t('Tout - xlarge'));

    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - small'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - xlarge'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - xsmall'));

    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - small'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - xlarge'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - xsmall'));

    $this->assertSession()->pageTextContains($this->t('Text teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Text teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Text teaser - small'));

    // Check all check boxes for VMI custom display view modes.
    $this->getSession()->getPage()->checkField($this->t('Hero - xlarge'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Tout - large'));
    $this->getSession()->getPage()->checkField($this->t('Tout - medium'));
    $this->getSession()->getPage()->checkField($this->t('Tout - xlarge'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - small'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - xlarge'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - xsmall'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - small'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - xlarge'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - xsmall'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Text teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Text teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Text teaser - small'));
    $this->getSession()->getPage()->pressButton('Save');

    $this->drupalGet('admin/structure/types/manage/image_content/display/hero_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Hero content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/tout_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/tout_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/tout_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/vertical_media_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/vertical_media_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/vertical_media_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/vertical_media_teaser_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/vertical_media_teaser_xsmall');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/horizontal_media_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/horizontal_media_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/horizontal_media_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/horizontal_media_teaser_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/horizontal_media_teaser_xsmall');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main image'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/text_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/text_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/image_content/display/text_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));

  }

  /**
   * Test Display Video Content Type.
   */
  public function testDisplayVideoContentType() {
    $this->drupalGet('admin/structure/types/manage/video_content/display');
    $custom_display_settings_text = $this->t('Custom display settings');
    $this->assertSession()->pageTextContains($custom_display_settings_text);
    $this->clickLink($custom_display_settings_text);

    $this->assertSession()->pageTextContains($this->t('Use custom display settings for the following view modes'));

    // Make sure that we do have all VMI custom display view modes ready to use.
    $this->assertSession()->pageTextContains($this->t('Hero - xlarge'));

    $this->assertSession()->pageTextContains($this->t('Tout - large'));
    $this->assertSession()->pageTextContains($this->t('Tout - medium'));
    $this->assertSession()->pageTextContains($this->t('Tout - xlarge'));

    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - small'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - xlarge'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - xsmall'));

    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - small'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - xlarge'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - xsmall'));

    $this->assertSession()->pageTextContains($this->t('Text teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Text teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Text teaser - small'));

    // Check all check boxes for VMI custom display view modes.
    $this->getSession()->getPage()->checkField($this->t('Hero - xlarge'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Tout - large'));
    $this->getSession()->getPage()->checkField($this->t('Tout - medium'));
    $this->getSession()->getPage()->checkField($this->t('Tout - xlarge'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - small'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - xlarge'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - xsmall'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - small'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - xlarge'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - xsmall'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Text teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Text teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Text teaser - small'));
    $this->getSession()->getPage()->pressButton('Save');

    $this->drupalGet('admin/structure/types/manage/video_content/display/hero_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Hero content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/tout_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/tout_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/tout_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/vertical_media_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/vertical_media_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/vertical_media_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/vertical_media_teaser_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/vertical_media_teaser_xsmall');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/horizontal_media_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/horizontal_media_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/horizontal_media_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/horizontal_media_teaser_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/horizontal_media_teaser_xsmall');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main video'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/text_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/text_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/video_content/display/text_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
  }

  /**
   * Test Display Media Content Type.
   */
  public function testDisplayMediaContentType() {
    $this->drupalGet('admin/structure/types/manage/media_content/display');
    $custom_display_settings_text = $this->t('Custom display settings');
    $this->assertSession()->pageTextContains($custom_display_settings_text);
    $this->clickLink($custom_display_settings_text);

    $this->assertSession()->pageTextContains($this->t('Use custom display settings for the following view modes'));

    // Make sure that we do have all VMI custom display view modes ready to use.
    $this->assertSession()->pageTextContains($this->t('Hero - xlarge'));

    $this->assertSession()->pageTextContains($this->t('Tout - large'));
    $this->assertSession()->pageTextContains($this->t('Tout - medium'));
    $this->assertSession()->pageTextContains($this->t('Tout - xlarge'));

    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - small'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - xlarge'));
    $this->assertSession()->pageTextContains($this->t('Vertical media teaser - xsmall'));

    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - small'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - xlarge'));
    $this->assertSession()->pageTextContains($this->t('Horizontal media teaser - xsmall'));

    $this->assertSession()->pageTextContains($this->t('Text teaser - large'));
    $this->assertSession()->pageTextContains($this->t('Text teaser - medium'));
    $this->assertSession()->pageTextContains($this->t('Text teaser - small'));

    // Check all check boxes for VMI custom display view modes.
    $this->getSession()->getPage()->checkField($this->t('Hero - xlarge'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Tout - large'));
    $this->getSession()->getPage()->checkField($this->t('Tout - medium'));
    $this->getSession()->getPage()->checkField($this->t('Tout - xlarge'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - small'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - xlarge'));
    $this->getSession()->getPage()->checkField($this->t('Vertical media teaser - xsmall'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - small'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - xlarge'));
    $this->getSession()->getPage()->checkField($this->t('Horizontal media teaser - xsmall'));
    $this->getSession()->getPage()->pressButton('Save');
    $this->clickLink($custom_display_settings_text);
    $this->getSession()->getPage()->checkField($this->t('Text teaser - large'));
    $this->getSession()->getPage()->checkField($this->t('Text teaser - medium'));
    $this->getSession()->getPage()->checkField($this->t('Text teaser - small'));
    $this->getSession()->getPage()->pressButton('Save');

    $this->drupalGet('admin/structure/types/manage/media_content/display/hero_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Hero content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/tout_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/tout_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/tout_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Tout content'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/vertical_media_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/vertical_media_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/vertical_media_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/vertical_media_teaser_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/vertical_media_teaser_xsmall');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/horizontal_media_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/horizontal_media_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/horizontal_media_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/horizontal_media_teaser_xlarge');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/horizontal_media_teaser_xsmall');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Left'));
    $this->assertSession()->pageTextContains($this->t('Main media'));
    $this->assertSession()->pageTextContains($this->t('Right'));
    $this->assertSession()->pageTextContains($this->t('Title'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/text_teaser_large');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/text_teaser_medium');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
    $this->assertSession()->pageTextContains($this->t('Body'));

    $this->drupalGet('admin/structure/types/manage/media_content/display/text_teaser_small');
    $this->assertSession()->waitForElementVisible('css', '#field-display-overview');
    $this->assertSession()->pageTextContains($this->t('Title'));
  }

  /**
   * Create Image Content Type.
   */
  public function createImageContentType() {
    $this->imageContentType = $this->drupalCreateContentType([
      'type' => 'image_content',
      'name' => 'Image Content',
    ]);

    $storage = FieldStorageConfig::create([
      'entity_type' => 'node',
      'field_name' => 'field_image',
      'type' => 'entity_reference',
      'settings' => [
        'target_type' => 'media',
      ],
    ]);
    $storage->save();

    FieldConfig::create([
      'field_storage' => $storage,
      'entity_type' => 'node',
      'bundle' => 'image_content',
      'label' => 'Main image',
      'settings' => [
        'handler_settings' => [
          'target_bundles' => [
            'image' => 'image',
          ],
        ],
      ],
    ])->save();
  }

  /**
   * Create Video Content Type.
   */
  public function createVideoContentType() {
    $this->videoContentType = $this->drupalCreateContentType([
      'type' => 'video_content',
      'name' => 'Video Content',
    ]);

    $storage = FieldStorageConfig::create([
      'entity_type' => 'node',
      'field_name' => 'field_video',
      'type' => 'entity_reference',
      'settings' => [
        'target_type' => 'media',
      ],
    ]);
    $storage->save();

    FieldConfig::create([
      'field_storage' => $storage,
      'entity_type' => 'node',
      'bundle' => 'video_content',
      'label' => 'Main video',
      'settings' => [
        'handler_settings' => [
          'target_bundles' => [
            'video' => 'video',
            'remote_video' => 'remote_video',
          ],
        ],
      ],
    ])->save();
  }

  /**
   * Create Media Content Type.
   */
  public function createMediaContentType() {
    $this->mediaContentType = $this->drupalCreateContentType([
      'type' => 'media_content',
      'name' => 'Media Content',
    ]);

    $storage = FieldStorageConfig::create([
      'entity_type' => 'node',
      'field_name' => 'field_media',
      'type' => 'entity_reference',
      'settings' => [
        'target_type' => 'media',
      ],
    ]);
    $storage->save();

    FieldConfig::create([
      'field_storage' => $storage,
      'entity_type' => 'node',
      'bundle' => 'media_content',
      'label' => 'Main media',
      'settings' => [
        'handler_settings' => [
          'target_bundles' => [
            'image' => 'image',
            'video' => 'video',
            'remote_video' => 'remote_video',
          ],
        ],
      ],
    ])->save();
  }

}
