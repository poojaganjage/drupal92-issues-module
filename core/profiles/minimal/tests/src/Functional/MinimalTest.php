<?php

namespace Drupal\Tests\minimal\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\RequirementsPageTrait;
use Drupal\user\UserInterface;

/**
 * Tests Minimal installation profile expectations.
 *
 * @group minimal
 */
class MinimalTest extends BrowserTestBase {

  use RequirementsPageTrait;

  protected $profile = 'minimal';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests Minimal installation profile.
   */
  public function testMinimal() {
    $this->drupalGet('');
    // Check the login block is present.
    $this->assertSession()->linkExists('Create new account');
    $this->assertSession()->statusCodeEquals(200);

    // Create a user to test tools and navigation blocks for logged in users
    // with appropriate permissions.
    $user = $this->drupalCreateUser([
      'access administration pages',
      'administer content types',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet('');
    $this->assertText(t('Tools'));
    $this->assertText(t('Administration'));

    // Ensure that there are no pending updates after installation.
    $this->drupalLogin($this->rootUser);
    $this->drupalGet('update.php/selection');
    $this->updateRequirementsProblem();
    $this->drupalGet('update.php/selection');
    $this->assertText('No pending updates.');

    // Ensure that there are no pending entity updates after installation.
    $this->assertFalse($this->container->get('entity.definition_update_manager')->needsUpdates(), 'After installation, entity schema is up to date.');

    // Ensure special configuration overrides are correct.
    $this->assertFalse($this->config('system.theme.global')->get('features.node_user_picture'), 'Configuration system.theme.global:features.node_user_picture is FALSE.');
    $this->assertEquals(UserInterface::REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL, $this->config('user.settings')->get('register'));
  }

}
