<?php
/**
 * This file is part of the proophsoftware/event-machine.
 * (c) 2017-2018 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\EventMachine\JsonSchema\Type;

use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\Type;

class IntType implements Type
{
    use NullableType;

    /**
     * @var string|array
     */
    private $type = JsonSchema::TYPE_INT;

    /**
     * @var null|array
     */
    private $validation;

    public function __construct(array $validation = null)
    {
        $this->validation = $validation;
    }

    public function toArray(): array
    {
        return array_merge(['type' => $this->type], (array) $this->validation);
    }

    public function withMinimum(int $min): self
    {
        $cp = clone $this;

        $validation = (array) $this->validation;

        $validation['minimum'] = $min;

        $cp->validation = $validation;

        return $cp;
    }

    public function withMaximum(int $max): self
    {
        $cp = clone $this;

        $validation = (array) $this->validation;

        $validation['maximum'] = $max;

        $cp->validation = $validation;

        return $cp;
    }

    public function withRange(int $min, int $max): self
    {
        $cp = clone $this;

        $validation = (array) $this->validation;

        $validation['minimum'] = $min;
        $validation['maximum'] = $max;

        $cp->validation = $validation;

        return $cp;
    }
}
