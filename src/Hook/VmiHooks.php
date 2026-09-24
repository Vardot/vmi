<?php

declare(strict_types=1);

namespace Drupal\vmi\Hook;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Hook\Order\OrderAfter;
use Drupal\Core\Hook\Order\OrderBefore;
use Drupal\Core\Render\Element;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\vmi\ViewModesInventoryFactory;

/**
 * Hook implementations for the View Modes Inventory module.
 */
class VmiHooks {

  /**
   * The view mode that renders the full content of a node.
   */
  protected const CONTENT_VIEW_MODE = 'full';

  /**
   * The module that treats the node default display as a Canvas template.
   */
  protected const CANVAS_HELPER_MODULE = 'drupal_cms_helper';

  /**
   * The local task key of the node template tab.
   */
  protected const TEMPLATE_TASK = 'entity.node.template';

  /**
   * Element access and weight of the node default display form, keyed by name.
   *
   * NULL when the current form is not the node default display form.
   *
   * @var array<string, array{access: mixed, weight: mixed}>|null
   */
  protected ?array $defaultDisplayElements = NULL;

  /**
   * Constructs a VmiHooks object.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The current route match.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   */
  public function __construct(
    protected readonly RouteMatchInterface $routeMatch,
    protected readonly ModuleHandlerInterface $moduleHandler,
  ) {}

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help(string $route_name, RouteMatchInterface $route_match): string {
    if ($route_name === 'help.page.vmi') {
      $output = '<h3>' . t('About') . '</h3>';
      $output .= '<br/>' . t('The <a href="https://www.drupal.org/project/vmi">View modes inventory</a> module has a set of template view modes that we typically use (some of them) in each website.');
      return $output;
    }
    return '';
  }

  /**
   * Implements hook_menu_local_tasks_alter().
   *
   * Points the node template tab at the full view mode display.
   */
  #[Hook('menu_local_tasks_alter', order: new OrderAfter(modules: [self::CANVAS_HELPER_MODULE]))]
  public function menuLocalTasksAlter(array &$data, string $route_name): void {
    if (!isset($data['tabs'][0][self::TEMPLATE_TASK])) {
      return;
    }

    $node_type = $this->routeMatch->getParameter('node')?->getType();
    if (empty($node_type)) {
      return;
    }

    $url = Url::fromRoute('entity.entity_view_display.node.view_mode', [
      'node_type' => $node_type,
      'view_mode_name' => self::CONTENT_VIEW_MODE,
    ]);

    $data['tabs'][0][self::TEMPLATE_TASK]['#link']['url'] = $url;
    $data['tabs'][0][self::TEMPLATE_TASK]['#access'] = $url->access(return_as_object: TRUE);
  }

  /**
   * Implements hook_form_entity_view_display_edit_form_alter().
   *
   * Records the node default display form before it is treated as a template.
   */
  #[Hook('form_entity_view_display_edit_form_alter', order: new OrderBefore(modules: [self::CANVAS_HELPER_MODULE]))]
  public function captureNodeDefaultDisplayForm(array &$form): void {
    $this->defaultDisplayElements = NULL;

    if (!$this->moduleHandler->moduleExists(self::CANVAS_HELPER_MODULE)
      || ($form['#entity_type'] ?? NULL) !== 'node'
      || $this->routeMatch->getParameter('view_mode_name') !== 'default') {
      return;
    }

    $elements = [];
    foreach (Element::children($form) as $key) {
      $elements[$key] = [
        'access' => $form[$key]['#access'] ?? NULL,
        'weight' => $form[$key]['#weight'] ?? NULL,
      ];
    }
    $this->defaultDisplayElements = $elements;
  }

  /**
   * Implements hook_form_entity_view_display_edit_form_alter().
   *
   * Keeps the node default display form editable and free of template links.
   */
  #[Hook('form_entity_view_display_edit_form_alter', order: new OrderAfter(modules: [self::CANVAS_HELPER_MODULE]))]
  public function restoreNodeDefaultDisplayForm(array &$form): void {
    if ($this->defaultDisplayElements === NULL) {
      return;
    }

    foreach ($this->defaultDisplayElements as $key => $properties) {
      if (!isset($form[$key])) {
        continue;
      }
      foreach ($properties as $property => $value) {
        if ($value === NULL) {
          unset($form[$key]['#' . $property]);
        }
        else {
          $form[$key]['#' . $property] = $value;
        }
      }
    }

    unset($form['canvas_message'], $form['canvas_button']);
    $this->defaultDisplayElements = NULL;
  }

  /**
   * Implements hook_form_entity_view_display_edit_form_alter().
   */
  #[Hook('form_entity_view_display_edit_form_alter')]
  public function formEntityViewDisplayEditFormAlter(array &$form, FormStateInterface $form_state): void {
    $form['actions']['submit']['#submit'][] = [static::class, 'entityViewDisplayEditFormSubmit'];
  }

  /**
   * Apply mapped view modes inventory config when a view mode is activated.
   *
   * Only applies templates for view modes that were not previously enabled,
   * i.e. newly activated on the Manage Display form.
   */
  public static function entityViewDisplayEditFormSubmit(array $form, FormStateInterface $form_state): void {

    if (!isset($form['modes']['display_modes_custom'])) {
      return;
    }

    $vmi_factory = \Drupal::service('class_resolver')
      ->getInstanceFromDefinition(ViewModesInventoryFactory::class);

    $entity_type = $form['#entity_type'];
    $bundle_name = $form['#bundle'];

    $vmi_list = $vmi_factory->getViewModesList();
    $vmi_layouts_mapping = $vmi_factory->getLayoutsMapping();

    if (!isset($vmi_list['view_modes']) || !isset($vmi_layouts_mapping['mapping'])) {
      return;
    }

    // View modes that were already enabled before this form submission.
    $enabled_view_modes = $form['modes']['display_modes_custom']['#default_value'];

    // View modes selected in this form submission.
    $selected_view_modes = $form['modes']['display_modes_custom']['#value'];

    foreach ($selected_view_modes as $selected_view_mode) {
      // Only when the view mode was just activated (not previously enabled).
      if (in_array($selected_view_mode, $enabled_view_modes)
        || !isset($vmi_list['view_modes'][$selected_view_mode])
        || !isset($vmi_layouts_mapping['mapping'][$selected_view_mode])) {
        continue;
      }

      $vmi_factory->mapViewModeWithLayout(
        $selected_view_mode,
        $entity_type,
        $bundle_name,
        $vmi_layouts_mapping['mapping'][$selected_view_mode]
      );
    }
  }

}
