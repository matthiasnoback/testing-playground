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

  Scenario: Delivering more than we currently have in stock
    Given I have previously received product "A", quantity 4
    When I place a sales order with product "A", quantity 7
    Then I can't create a delivery note for this sales order, delivering 7 items of product "A" because "Sales order is not deliverable."

  Scenario: Making a reservation of goods
    Given I have previously received product "A", quantity 4
    When I place a sales order with product "A", quantity 3
    Then the stock level for product "A" will be 1

  Scenario:
    Given I have previously received product "A", quantity 4
    And I placed a sales order with product "A", quantity 3
    When I cancel this sales order
    Then the stock level for product "A" will be 4
