<?php
declare(strict_types=1);

namespace Common;

use Assert\Assertion;

abstract class AggregateId
{
    /**
     * @var string
     */
    private $id;

    private function __construct()
    {
    }

    /**
     * @param string $id
     * @return static
     */
    public static function fromString(string $id)
    {
        Assertion::notEmpty($id);
        Assertion::uuid($id);

        $aggregateId = new static();
        $aggregateId->id = $id;

        return $aggregateId;
    }

    public function asString(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string)substr($this->id, -5);
    }
}
