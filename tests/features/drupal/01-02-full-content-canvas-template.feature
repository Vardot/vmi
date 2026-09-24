@regression @any @admin @canvas
Feature: Content Structure - Full content Drupal Canvas template
      As a site admin user
      I want the full content display of a Canvas backed content type to open its template
      So that I can edit what a node renders as.

  @check @local @development @staging @production
  Scenario: Check that the full content display opens the Drupal Canvas template
    Given I am a logged in user with the "webmaster" user
     When I go to "/admin/structure/types/manage/vmi_test/display/full"
     Then the url should match "/canvas/template/node/vmi_test/full"
