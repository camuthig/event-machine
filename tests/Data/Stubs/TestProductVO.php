<?php

declare(strict_types=1);

namespace Prooph\EventMachineTest\Data\Stubs;

use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class TestProductVO implements ImmutableRecord
{
    use ImmutableRecordLogic;

    /**
     * @var TestProductIdVO
     */
    private $id;

    /**
     * @var TestProductNameVO
     */
    private $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var TestProductPriceVO
     */
    private $price;

    /**
     * @var TestProductActiveFlagVO
     */
    private $active;

    /**
     * @var array
     */
    private $tags;

    /**
     * @return TestProductIdVO
     */
    public function id(): TestProductIdVO
    {
        return $this->id;
    }

    /**
     * @return TestProductNameVO
     */
    public function name(): TestProductNameVO
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return TestProductPriceVO
     */
    public function price(): TestProductPriceVO
    {
        return $this->price;
    }

    /**
     * @return TestProductActiveFlagVO
     */
    public function active(): TestProductActiveFlagVO
    {
        return $this->active;
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return $this->tags;
    }
}