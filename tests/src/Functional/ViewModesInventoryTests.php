<?php

namespace Drupal\Tests\vmi\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * View Modes Inventory tests.
 *
 * @group vmi
 */
class ViewModesInventoryTests extends BrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'olivero';

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
  protected function setUp(): void {
    parent::setUp();

    \Drupal::service('theme_installer')->install(['claro']);

    \Drupal::configFactory()
      ->getEditable('system.theme')
      ->set('admin', 'claro')
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
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('/admin/structure/types/manage/image_content/display');

    // Check all check boxes for VMI custom display view modes.
    $page->checkField($this->t('Featured card - large'));
    $page->checkField($this->t('Featured card - medium'));
    $page->checkField($this->t('Featured card - small'));
    $page->checkField($this->t('Featured card - xlarge'));
    $page->checkField($this->t('Featured card - xsmall'));
    $page->checkField($this->t('Impressed card - large'));
    $page->checkField($this->t('Impressed card - medium'));
    $page->checkField($this->t('Impressed card - small'));
    $page->checkField($this->t('Impressed card - xlarge'));
    $page->checkField($this->t('Impressed card - xsmall'));
    $page->checkField($this->t('Overlay card - large'));
    $page->checkField($this->t('Overlay card - medium'));
    $page->checkField($this->t('Overlay card - xlarge'));
    $page->checkField($this->t('Text card - large'));
    $page->checkField($this->t('Text card - medium'));
    $page->checkField($this->t('Text card - small'));
    $page->checkField($this->t('Hero card'));
    $page->pressButton('Save');

    $this->drupalGet('/admin/structure/types/manage/image_content/display/hero_card');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/overlay_card_large');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/overlay_card_medium');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/overlay_card_xlarge');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/impressed_card_large');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/impressed_card_medium');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/impressed_card_small');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/impressed_card_xlarge');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/impressed_card_xsmall');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/featured_card_large');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/featured_card_medium');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/featured_card_small');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/featured_card_xlarge');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/featured_card_xsmall');
    $assert_session->pageTextContains($this->t('Main image'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/text_card_large');
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/text_card_medium');
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/image_content/display/text_card_small');
    $assert_session->pageTextContains($this->t('Title'));

  }

  /**
   * Test Display Video Content Type.
   */
  public function testDisplayVideoContentType() {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('/admin/structure/types/manage/video_content/display');

    // Check all check boxes for VMI custom display view modes.
    $page->checkField($this->t('Featured card - large'));
    $page->checkField($this->t('Featured card - medium'));
    $page->checkField($this->t('Featured card - small'));
    $page->checkField($this->t('Featured card - xlarge'));
    $page->checkField($this->t('Featured card - xsmall'));
    $page->checkField($this->t('Impressed card - large'));
    $page->checkField($this->t('Impressed card - medium'));
    $page->checkField($this->t('Impressed card - small'));
    $page->checkField($this->t('Impressed card - xlarge'));
    $page->checkField($this->t('Impressed card - xsmall'));
    $page->checkField($this->t('Overlay card - large'));
    $page->checkField($this->t('Overlay card - medium'));
    $page->checkField($this->t('Overlay card - xlarge'));
    $page->checkField($this->t('Text card - large'));
    $page->checkField($this->t('Text card - medium'));
    $page->checkField($this->t('Text card - small'));
    $page->checkField($this->t('Hero card'));
    $page->pressButton('Save');

    $this->drupalGet('/admin/structure/types/manage/video_content/display/hero_card');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/overlay_card_large');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/overlay_card_medium');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/overlay_card_xlarge');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/impressed_card_large');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/impressed_card_medium');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/impressed_card_small');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/impressed_card_xlarge');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/impressed_card_xsmall');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/featured_card_large');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/featured_card_medium');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/featured_card_small');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/featured_card_xlarge');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/featured_card_xsmall');
    $assert_session->pageTextContains($this->t('Main video'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/text_card_large');
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/text_card_medium');
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/video_content/display/text_card_small');
    $assert_session->pageTextContains($this->t('Title'));
  }

  /**
   * Test Display Media Content Type.
   */
  public function testDisplayMediaContentType() {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('/admin/structure/types/manage/media_content/display');

    // Check all check boxes for VMI custom display view modes.
    $page->checkField($this->t('Featured card - large'));
    $page->checkField($this->t('Featured card - medium'));
    $page->checkField($this->t('Featured card - small'));
    $page->checkField($this->t('Featured card - xlarge'));
    $page->checkField($this->t('Featured card - xsmall'));
    $page->checkField($this->t('Impressed card - large'));
    $page->checkField($this->t('Impressed card - medium'));
    $page->checkField($this->t('Impressed card - small'));
    $page->checkField($this->t('Impressed card - xlarge'));
    $page->checkField($this->t('Impressed card - xsmall'));
    $page->checkField($this->t('Overlay card - large'));
    $page->checkField($this->t('Overlay card - medium'));
    $page->checkField($this->t('Overlay card - xlarge'));
    $page->checkField($this->t('Text card - large'));
    $page->checkField($this->t('Text card - medium'));
    $page->checkField($this->t('Text card - small'));
    $page->checkField($this->t('Hero card'));
    $page->pressButton('Save');

    $this->drupalGet('/admin/structure/types/manage/media_content/display/hero_card');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/overlay_card_large');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/overlay_card_medium');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/overlay_card_xlarge');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/impressed_card_large');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/impressed_card_medium');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/impressed_card_small');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/impressed_card_xlarge');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/impressed_card_xsmall');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/featured_card_large');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/featured_card_medium');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/featured_card_small');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/featured_card_xlarge');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/featured_card_xsmall');
    $assert_session->pageTextContains($this->t('Main media'));
    $assert_session->pageTextContains($this->t('Title'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/text_card_large');
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/text_card_medium');
    $assert_session->pageTextContains($this->t('Title'));
    $assert_session->pageTextContains($this->t('Body'));

    $this->drupalGet('/admin/structure/types/manage/media_content/display/text_card_small');
    $assert_session->pageTextContains($this->t('Title'));
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
