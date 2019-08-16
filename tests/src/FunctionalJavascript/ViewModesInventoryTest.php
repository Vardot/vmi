<?php

namespace Drupal\Tests\vmi\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

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
   * Tests View Modes Inventory.
   */
  public function testViewModesInventory() {
    $this->TestDisplayImageContentType();
    $this->TestDisplayVideoContentType();
    $this->TestDisplayMediaContentType();
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
      'label' => 'Main Image',
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
      'label' => 'Main Video',
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
      'label' => 'Main Media',
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
