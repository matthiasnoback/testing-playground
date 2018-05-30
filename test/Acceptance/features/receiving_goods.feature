Feature: Receiving goods

  Background:
    Given the catalog contains product "A"

  Scenario: Receive part of the ordered goods
    Given I placed a purchase order with product "A", quantity 2.0
     When I create a receipt note for this purchase order, receiving 1.0 items of product "A"
     Then the stock level for product "A" will be 1.0
      And I expect the purchase order not to be fully delivered yet

  Scenario: Receive all the ordered goods at once
    Given I placed a purchase order with product "A", quantity 2.0
     When I create a receipt note for this purchase order, receiving 2.0 items of product "A"
     Then the stock level for product "A" will be 2.0
      And I expect the purchase order to be fully delivered

  Scenario: Receive all the ordered goods in multiple batches
    Given I placed a purchase order with product "A", quantity 3.0
      And I created a receipt note for this purchase order, receiving 1.0 items of product "A"
     When I create a receipt note for this purchase order, receiving 2.0 items of product "A"
     Then the stock level for product "A" will be 3.0
      And I expect the purchase order to be fully delivered

  Scenario: Receive part of the ordered goods, then undo the receipt
    Given I placed a purchase order with product "A", quantity 2.0
      And I created a receipt note for this purchase order, receiving 1.0 items of product "A"
     When I undo the receipt
     Then the stock level for product "A" will be 0.0
      And I expect the purchase order not to be fully delivered yet

  Scenario: Receive all the ordered goods, then undo the receipt
    Given I placed a purchase order with product "A", quantity 2.0
      And I created a receipt note for this purchase order, receiving 2.0 items of product "A"
     When I undo the receipt
     Then the stock level for product "A" will be 0.0
      And I expect the purchase order not to be fully delivered yet