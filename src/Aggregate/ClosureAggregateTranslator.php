<?php
/**
 * This file is part of the proophsoftware/event-machine.
 * (c) 2017-2018 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\EventMachine\Aggregate;

use Iterator;
use Prooph\Common\Messaging\Message;
use Prooph\EventSourcing\Aggregate\AggregateTranslator as EventStoreAggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;

/**
 * Class ClosureAggregateTranslator
 *
 * Special version of prooph/event-sourcing ClosureAggregateTranslator that uses GenericAggregateRoot to reconstitute the aggregate
 *
 * @package Prooph\EventSourcing\EventStoreIntegration
 */
final class ClosureAggregateTranslator implements EventStoreAggregateTranslator
{
    protected $aggregateReconstructor;
    protected $pendingEventsExtractor;
    protected $replayStreamEvents;
    protected $versionExtractor;

    /**
     * @var string
     */
    private $aggregateId;

    private $eventApplyMap;

    public function __construct(string $aggregateId, array $eventApplyMap)
    {
        $this->aggregateId = $aggregateId;
        $this->eventApplyMap = $eventApplyMap;
    }

    /**
     * @param object $eventSourcedAggregateRoot
     *
     * @return int
     */
    public function extractAggregateVersion($eventSourcedAggregateRoot): int
    {
        if (null === $this->versionExtractor) {
            $this->versionExtractor = function (): int {
                return $this->version;
            };
        }

        return $this->versionExtractor->call($eventSourcedAggregateRoot);
    }

    /**
     * @param object $anEventSourcedAggregateRoot
     *
     * @return string
     */
    public function extractAggregateId($anEventSourcedAggregateRoot): string
    {
        return $this->aggregateId;
    }

    /**
     * @param AggregateType $aggregateType
     * @param Iterator $historyEvents
     *
     * @return object reconstructed AggregateRoot
     */
    public function reconstituteAggregateFromHistory(AggregateType $aggregateType, Iterator $historyEvents)
    {
        if (null === $this->aggregateReconstructor) {
            $arId = $this->aggregateId;
            $eventApplyMap = $this->eventApplyMap;
            $this->aggregateReconstructor = function ($historyEvents) use ($arId, $aggregateType, $eventApplyMap) {
                return static::reconstituteFromHistory($arId, $aggregateType, $eventApplyMap, $historyEvents);
            };
        }

        return ($this->aggregateReconstructor->bindTo(null, GenericAggregateRoot::class))($historyEvents);
    }

    /**
     * @param object $anEventSourcedAggregateRoot
     *
     * @return Message[]
     */
    public function extractPendingStreamEvents($anEventSourcedAggregateRoot): array
    {
        if (null === $this->pendingEventsExtractor) {
            $this->pendingEventsExtractor = function (): array {
                return $this->popRecordedEvents();
            };
        }

        return $this->pendingEventsExtractor->call($anEventSourcedAggregateRoot);
    }

    /**
     * @param object $anEventSourcedAggregateRoot
     * @param Iterator $events
     *
     * @return void
     */
    public function replayStreamEvents($anEventSourcedAggregateRoot, Iterator $events): void
    {
        if (null === $this->replayStreamEvents) {
            $this->replayStreamEvents = function ($events): void {
                $this->replay($events);
            };
        }
        $this->replayStreamEvents->call($anEventSourcedAggregateRoot, $events);
    }
}
