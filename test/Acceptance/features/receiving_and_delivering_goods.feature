Feature:

  Background:
    Given the catalog contains product "A"

  Scenario: Receiving goods increases the balance
    Given I placed a purchase order with product "A", quantity 2
    When I create a receipt note for this purchase order, receiving 2 items of product "A"
    Then the stock level for product "A" will be 2

  Scenario: Delivering goods decreases the balance
    Given I have previously received product "A", quantity 7
    And I placed a sales order with product "A", quantity 4
    When I create a delivery note for this sales order, delivering 4 items of product "A"
    Then the stock level for product "A" will be 3
