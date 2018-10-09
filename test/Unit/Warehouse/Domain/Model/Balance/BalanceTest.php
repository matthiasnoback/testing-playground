<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Balance;

use PHPUnit\Framework\TestCase;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

final class BalanceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_make_a_reservation_if_there_is_more_than_the_ordered_quantity(): void
    {
        $productId = ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880');
        $salesOrderId = SalesOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39');
        $balance = new Balance($productId);

        $balance->increase(4);
        $balance->recordedEvents();

        $balance->makeReservation($salesOrderId, 2);

        $events = $balance->recordedEvents();
        self::assertEquals(2, count($events));
        self::assertInstanceOf(StockLevelChanged::class, $events[0]);
        self::assertInstanceOf(ReservationAccepted::class, $events[1]);
    }

    /**
     * @test
     */
    public function it_can_make_a_reservation_if_there_is_the_same_as_the_ordered_quantity(): void
    {
        $productId = ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880');
        $salesOrderId = SalesOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39');
        $balance = new Balance($productId);

        $balance->increase(2);
        $balance->recordedEvents();
        $balance->makeReservation($salesOrderId, 2);

        $events = $balance->recordedEvents();
        self::assertEquals(2, count($events));
        self::assertInstanceOf(StockLevelChanged::class, $events[0]);
        self::assertInstanceOf(ReservationAccepted::class, $events[1]);
    }

    /**
     * @test
     */
    public function it_can_make_a_reservation_until_the_stock_depleted(): void
    {
        $productId = ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880');
        $salesOrderId = SalesOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39');
        $balance = new Balance($productId);

        $balance->increase(2);
        $balance->recordedEvents();

        $balance->makeReservation($salesOrderId, 2);
        $balance->makeReservation($salesOrderId, 2);
        $events = $balance->recordedEvents();

        self::assertEquals(3, count($events));
        self::assertInstanceOf(StockLevelChanged::class, $events[0]);
        self::assertInstanceOf(ReservationAccepted::class, $events[1]);
        self::assertInstanceOf(ReservationRejected::class, $events[2]);
    }

    /**
     * @test
     */
    public function it_cannot_make_a_reservation_if_there_is_no_stock(): void
    {
        $productId = ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880');
        $salesOrderId = SalesOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39');

        $balance = new Balance($productId);

        $balance->makeReservation($salesOrderId, 2);
        $events = $balance->recordedEvents();
        self::assertEquals(1, count($events));
        self::assertInstanceOf(ReservationRejected::class, $events[0]);
    }
}
