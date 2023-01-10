<?php

declare(strict_types=1);

namespace Omnicolor\Calendar\Tests;

use Carbon\CarbonImmutable as Date;
use Omnicolor\Calendar\CalendarStorage;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class CalendarStorageTest extends TestCase
{
    public function testAddingMultipleDates(): void
    {
        $storage = new CalendarStorage();
        self::assertCount(0, $storage);
        $storage->attach(new Date('2010-01-01'));
        self::assertCount(1, $storage);
        $storage->attach(new Date('2011-01-01'));
        self::assertCount(2, $storage);
    }

    public function testAddingSameDate(): void
    {
        $storage = new CalendarStorage();
        $storage->attach(new Date('2010-01-01'));
        self::assertCount(1, $storage);
        $storage->attach(new Date('2010-01-01'));
        self::assertCount(1, $storage);
    }

    public function testAddingSameDateDifferentTime(): void
    {
        $storage = new CalendarStorage();
        $storage->attach(new Date('2010-01-01 13:00:00'));
        self::assertCount(1, $storage);
        $storage->attach(new Date('2010-01-01 14:00:00'));
        self::assertCount(1, $storage);
    }
}
