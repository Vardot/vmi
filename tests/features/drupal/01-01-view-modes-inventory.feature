@regression @any @admin
Feature: Content Structure - View Modes Inventory
      As a site admin user
      I want the View Modes Inventory view modes listed for a content type
      So that I can enable the ones the site needs and manage their displays.

  @check @local @development @staging @production
  Scenario: Check the view modes listing for the VMI test content type
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/structure/types/manage/vmi_test/display"
      And wait
     Then I should see "Enabled view modes"
      And I should see "Disabled view modes"
      And I should see "View mode"
      And I should see "Operations"
      And I should see the "Manage (Default)" in the "Default" row
      And I should see the "Manage (Full content)" in the "Full content" row
      And I should see the "Disable (Full content)" in the "Full content" row

  @check @local @development @staging @production
  Scenario: Check that the View Modes Inventory view modes are available to enable
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/structure/types/manage/vmi_test/display"
      And wait
     Then I should see the "Enable (Hero card)" in the "Hero card" row
      And I should see the "Enable (Featured card - large)" in the "Featured card - large" row
      And I should see the "Enable (Impressed card - medium)" in the "Impressed card - medium" row
      And I should see the "Enable (Overlay card - large)" in the "Overlay card - large" row
      And I should see the "Enable (Text card - small)" in the "Text card - small" row

  @check @local @development @staging @production
  Scenario: Check that a View Modes Inventory view mode can be enabled and disabled
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/structure/types/manage/vmi_test/display"
      And I open the "Enable (Hero card)" link in the "Hero card" row
      And wait
     Then I should see "The Hero card view mode has been enabled."
      And I should see the "Manage (Hero card)" in the "Hero card" row
      And I should see the "Disable (Hero card)" in the "Hero card" row
     When I open the "Disable (Hero card)" link in the "Hero card" row
      And wait
     Then I should see "The Hero card view mode has been disabled."
      And I should see the "Enable (Hero card)" in the "Hero card" row

  @check @local @development @staging @production
  Scenario: Check that the View Modes Inventory view modes are listed as node display modes
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/structure/display-modes/view"
      And wait
     Then I should see "Hero card"
      And I should see "Featured card - large"
      And I should see "Impressed card - medium"
      And I should see "Overlay card - large"
      And I should see "Text card - small"

  @check @local @development @staging @production
  Scenario: Check that anonymous users can not reach the view modes listing
    Given I am an anonymous user
     When I go to "/admin/structure/types/manage/vmi_test/display"
      And wait
     Then I should be denied access
