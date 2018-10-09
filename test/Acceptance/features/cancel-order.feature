Feature: Cancel an order

  Scenario: Cancel an order frees the reserved items
    Given a product "My Product"
    And I have received 10 items of this product
    And I create a sales order for 4 items of this product
    When I cancel it
    Then the balance for this product should be 10
