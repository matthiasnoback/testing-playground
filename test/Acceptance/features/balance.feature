Feature: Stock balancing

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

  @ignore
  Scenario: Fail to deliver goods when stock level is insufficient
    Given a product "My product"
    When I create a sales order for 4 items of this product
    Then I can not deliver the sales order

  @ignore
  Scenario: Be able to deliver products with sufficient stock
    Given a product "My product"
    And I have received 10 items of this product
    When I create a sales order for 6 items of this product
    Then this sales order should be deliverable

  @ignore
  Scenario: Cancelling an order frees the reserved items
    Given a product "My Product"
    And I have received 10 items of this product
    And I have created a sales order for 4 items of this product
    When I cancel it
    Then the balance for this product should be 10
