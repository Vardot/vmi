@regression @any @admin @canvas
Feature: Content Structure - Node full content template
      As a site admin user
      I want the node template tab to open the full content display and the node
      default display to stay editable
      So that I can edit the Drupal Canvas template and the node display fields.

  @check @local @development @staging @production
  Scenario: Check that the node template tab points at the full content display
    Given I am a logged in user with the "webmaster" user
     When I go to "/vmi-test-node"
      And wait
     Then the link "Edit template" with the href "/admin/structure/types/manage/vmi_test/display/full" should exist
      And the link "Edit template" with the href "/admin/structure/types/manage/vmi_test/display/default" should not exist

  @check @local @development @staging @production
  Scenario: Check that the node edit form template tab points at the full content display
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/content"
      And I open the "Edit" link in the "VMI test node" row
      And wait
     Then the link "Edit template" with the href "/admin/structure/types/manage/vmi_test/display/full" should exist
      And the link "Edit template" with the href "/admin/structure/types/manage/vmi_test/display/default" should not exist

  @check @local @development @staging @production
  Scenario: Check that the node default display stays editable
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/structure/types/manage/vmi_test/display/default"
      And wait
     Then I should see "Machine name"
      And I should see "Region"
      And I should see "Label"
      And "#edit-submit" should be visible
      And I should not see "This display is handled by Drupal Canvas"
      And "#edit-canvas-button" should not be visible

  @check @local @development @staging @production
  Scenario: Check that the full content display opens the Drupal Canvas template
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/structure/types/manage/vmi_test/display/full"
     Then the url should match "/canvas/template/node/vmi_test/full"
