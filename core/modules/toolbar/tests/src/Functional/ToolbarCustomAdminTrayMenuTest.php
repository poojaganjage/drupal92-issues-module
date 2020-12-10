<?php

namespace Drupal\Tests\toolbar\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests overriding the toolbar's admin tray menu.
 *
 * @group toolbar
 */
class ToolbarCustomAdminTrayMenuTest extends BrowserTestBase {

  /**
   * A user with permission to access the toolbar.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'toolbar',
    'toolbar_custom_admin_tray_menu',
    'test_page_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create an administrative user and log it in.
    $this->adminUser = $this->drupalCreateUser([
      'access toolbar',
      'access administration pages',
    ]);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Tests the custom admin tray menu.
   */
  public function testToolbarCustomAdminTrayMenu() {
    $this->drupalGet('test-page');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the toolbar is present in the HTML.
    $this->assertRaw('id="toolbar-administration"');

    // Assert that the "Content" link not present.
    $this->assertNoRaw('href="/admin/content"');

    // Assert that the custom link is present.
    $this->assertRaw('Custom menu link');
  }

}
