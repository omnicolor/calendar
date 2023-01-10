<?php

declare(strict_types=1);

namespace Omnicolor\Calendar;

use ArrayAccess;
use Carbon\CarbonImmutable as Date;
use Carbon\CarbonPeriod;
use Countable;
use InvalidArgumentException;
use Iterator;

/**
 * @implements ArrayAccess<Date, mixed>
 * @implements Iterator<Date, mixed>
 */
class Calendar implements ArrayAccess, Countable, Iterator
{
    protected CalendarStorage $data;
    protected Date $end;

    public function __construct(protected Date $start, ?Date $end = null)
    {
        $this->data = new CalendarStorage();
        if (null === $end) {
            $end = $start;
        }
        $this->end = $end;

        $period = new CarbonPeriod($start, $end);
        $period->setDateClass(Date::class);
        /** @var Date $date */
        foreach ($period as $date) {
            $this->data->attach($date);
        }
    }

    public function count(): int
    {
        return $this->data->count();
    }

    public function current(): mixed
    {
        return $this->data[$this->data->current()];
    }

    /**
     * Extend the calendar.
     *
     * If the given date is before the calendar's start date, prepends dates.
     * Similiarly, if the date is after the calendar's end date, appends dates.
     * @param Date $date
     */
    public function extend(Date $date): void
    {
        if ($date < $this->start) {
            $period = new CarbonPeriod($date, $this->start, CarbonPeriod::EXCLUDE_END_DATE);
        } elseif ($date > $this->end) {
            $period = new CarbonPeriod($this->end, $date, CarbonPeriod::EXCLUDE_START_DATE);
        } else {
            return;
        }
        foreach ($period as $newDate) {
            $this->data->attach($date);
        }
    }

    public function getEnd(): Date
    {
        return $this->end;
    }

    public function getStart(): Date
    {
        return $this->start;
    }

    public function key(): Date
    {
        return $this->data->current();
    }

    public function next(): void
    {
        $this->data->next();
    }

    public function offsetExists(mixed $offset): bool
    {
        // @phpstan-ignore-next-line
        if (!($offset instanceof Date)) {
            throw new InvalidArgumentException();
        }
        return $this->data->contains($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        // @phpstan-ignore-next-line
        if (!($offset instanceof Date)) {
            throw new InvalidArgumentException();
        }
        return $this->data[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($offset instanceof Date)) {
            throw new InvalidArgumentException();
        }
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        // @phpstan-ignore-next-line
        if (!($offset instanceof Date)) {
            throw new InvalidArgumentException();
        }

        if (!$this->data->contains($offset)) {
            return;
        }

        $this->data->detach($offset);
    }

    public function rewind(): void
    {
        $this->data->rewind();
    }

    public function valid(): bool
    {
        return $this->data->valid();
    }
}
