<?php

declare(strict_types=1);

namespace Drupal\Tests\vmi\Unit;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Hook\Order\OrderAfter;
use Drupal\Core\Hook\Order\OrderBefore;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\vmi\Hook\VmiHooks;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the hook registration and ordering of the View Modes Inventory hooks.
 */
#[CoversClass(VmiHooks::class)]
#[Group('vmi')]
class VmiHooksTest extends UnitTestCase {

  /**
   * The module whose node display handling these hooks run around.
   */
  protected const CANVAS_HELPER_MODULE = 'drupal_cms_helper';

  /**
   * Returns the Hook attribute instances declared on a method.
   *
   * @param string $method
   *   The method name.
   *
   * @return \Drupal\Core\Hook\Attribute\Hook[]
   *   The hook attributes.
   */
  protected function hookAttributes(string $method): array {
    $reflection = new \ReflectionMethod(VmiHooks::class, $method);
    return array_map(
      static fn(\ReflectionAttribute $attribute) => $attribute->newInstance(),
      $reflection->getAttributes(Hook::class)
    );
  }

  /**
   * Tests that the template tab is altered after the Drupal CMS helper.
   */
  public function testTemplateTabRunsAfterTheCanvasHelper(): void {
    $attributes = $this->hookAttributes('menuLocalTasksAlter');
    $this->assertCount(1, $attributes);
    $this->assertSame('menu_local_tasks_alter', $attributes[0]->hook);
    $this->assertInstanceOf(OrderAfter::class, $attributes[0]->order);
    $this->assertSame(
      [self::CANVAS_HELPER_MODULE],
      $attributes[0]->order->modules
    );
  }

  /**
   * Tests that the display form is captured before the Drupal CMS helper.
   */
  public function testDisplayFormIsCapturedBeforeTheCanvasHelper(): void {
    $attributes = $this->hookAttributes('captureNodeDefaultDisplayForm');
    $this->assertCount(1, $attributes);
    $this->assertSame(
      'form_entity_view_display_edit_form_alter',
      $attributes[0]->hook
    );
    $this->assertInstanceOf(OrderBefore::class, $attributes[0]->order);
    $this->assertSame(
      [self::CANVAS_HELPER_MODULE],
      $attributes[0]->order->modules
    );
  }

  /**
   * Tests that the display form is restored after the Drupal CMS helper.
   */
  public function testDisplayFormIsRestoredAfterTheCanvasHelper(): void {
    $attributes = $this->hookAttributes('restoreNodeDefaultDisplayForm');
    $this->assertCount(1, $attributes);
    $this->assertSame(
      'form_entity_view_display_edit_form_alter',
      $attributes[0]->hook
    );
    $this->assertInstanceOf(OrderAfter::class, $attributes[0]->order);
    $this->assertSame(
      [self::CANVAS_HELPER_MODULE],
      $attributes[0]->order->modules
    );
  }

  /**
   * Tests that local tasks without a template tab are left alone.
   */
  public function testLocalTasksWithoutATemplateTabAreUntouched(): void {
    $route_match = $this->createMock(RouteMatchInterface::class);
    $route_match->expects($this->never())->method('getParameter');
    $hooks = new VmiHooks(
      $route_match,
      $this->createMock(ModuleHandlerInterface::class)
    );

    $data = ['tabs' => [0 => ['entity.node.canonical' => ['#weight' => 0]]]];
    $expected = $data;
    $hooks->menuLocalTasksAlter($data, 'entity.node.canonical');

    $this->assertSame($expected, $data);
  }

  /**
   * Tests that the display form is not captured without the Drupal CMS helper.
   */
  public function testDisplayFormIsNotCapturedWithoutTheCanvasHelper(): void {
    $module_handler = $this->createMock(ModuleHandlerInterface::class);
    $module_handler->method('moduleExists')
      ->with(self::CANVAS_HELPER_MODULE)
      ->willReturn(FALSE);
    $route_match = $this->createMock(RouteMatchInterface::class);
    $route_match->expects($this->never())->method('getParameter');
    $hooks = new VmiHooks($route_match, $module_handler);

    $form = ['#entity_type' => 'node', 'status' => ['#access' => TRUE]];
    $expected = $form;
    $hooks->captureNodeDefaultDisplayForm($form);

    $this->assertSame($expected, $form);
  }

}
