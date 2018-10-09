Feature: Delivery

  Scenario: Be able to deliver products with sufficient stock
    Given a product "My product"
    And I have received 10 items of this product
    When I create a sales order for 6 items of this product
    Then this sales order should be deliverable
