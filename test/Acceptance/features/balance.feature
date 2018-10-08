Feature: Balance updates

  Scenario: A new product has no stock
    When I create a product "My product"
    Then the balance for this product should be 0

  Scenario: After receiving goods stock is increased
    Given a product "My product"
    When I receive 10 items of this product
    Then the balance for this product should be 10

  Scenario: After delivering goods stock is decreased
    Given a product "My product"
    And I have received 10 items of this product
    When I deliver 4 items of this product
    Then the balance for this product should be 6
