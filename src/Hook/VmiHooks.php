<?php

declare(strict_types=1);

namespace Drupal\vmi\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\vmi\ViewModesInventoryFactory;

/**
 * Hook implementations for the View Modes Inventory module.
 */
class VmiHooks {

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
