<?php

declare(strict_types=1);

namespace Omnicolor\Calendar;

use Carbon\CarbonImmutable as Date;
use SplObjectStorage;

/**
 * @extends SplObjectStorage<Date, mixed>
 */
class CalendarStorage extends SplObjectStorage
{
    /**
     * @param Date $object
     */
    public function getHash(object $object): string
    {
        return $object->toDateString();
    }
}
