Feature: Receiving goods

  Background:
    Given the catalog contains product "A"

  Scenario: Receive part of the ordered goods
    Given I placed a purchase order with product "A", quantity 2.0
     When I create a receipt note for this purchase order, receiving 1.0 items of product "A"
     Then I expect the purchase order not to be fully delivered yet

  Scenario: Receive all the ordered goods at once
    Given I placed a purchase order with product "A", quantity 2.0
     When I create a receipt note for this purchase order, receiving 2.0 items of product "A"
     Then I expect the purchase order to be fully delivered yet

  Scenario: Receive all the ordered goods in multiple batches
    Given I placed a purchase order with product "A", quantity 2.0
     When I create a receipt note for this purchase order, receiving 2.0 items of product "A"
     Then I expect the purchase order to be fully delivered yet
