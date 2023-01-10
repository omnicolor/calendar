<?php

declare(strict_types=1);

namespace Omnicolor\Calendar\Tests;

use Carbon\CarbonImmutable as Date;
use InvalidArgumentException;
use Omnicolor\Calendar\Calendar;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @small
 */
final class CalendarTest extends TestCase
{
    public function testStartEnd(): void
    {
        $start = new Date('2023-04-01');
        $end = new Date('2023-04-30');
        $calendar = new Calendar($start, $end);

        self::assertSame($start, $calendar->getStart());
        self::assertSame($end, $calendar->getEnd());
    }

    public function testCount(): void
    {
        $start = new Date('2023-04-01');
        $end = new Date('2023-04-30');
        $calendar = new Calendar($start, $end);

        self::assertCount(30, $calendar);

        $end = new Date('2023-05-31');
        $calendar = new Calendar($start, $end);
        self::assertCount(61, $calendar);
    }

    public function testOffsetExistsIncorrectType(): void
    {
        self::expectException(InvalidArgumentException::class);
        $start = new Date('2023-04-01');
        $calendar = new Calendar($start);

        // @phpstan-ignore-next-line
        echo isset($calendar[new stdClass()]);
    }

    public function testOffsetExists(): void
    {
        $start = new Date('2023-04-01');
        $end = new Date('2023-04-30');
        $calendar = new Calendar($start, $end);

        self::assertTrue(isset($calendar[new Date('2023-04-03')]));
        self::assertFalse(isset($calendar[new Date('2023-05-01')]));
    }

    public function testOffsetGetIncorrectType(): void
    {
        self::expectException(InvalidArgumentException::class);
        $start = new Date('2023-04-01');
        $calendar = new Calendar($start);

        // @phpstan-ignore-next-line
        echo $calendar[new stdClass()];
    }

    public function testOffsetSetIncorrectType(): void
    {
        self::expectException(InvalidArgumentException::class);
        $start = new Date('2023-04-01');
        $calendar = new Calendar($start);

        // @phpstan-ignore-next-line
        $calendar[new stdClass()] = 'testing';
    }

    public function testOffsetSet(): void
    {
        $start = new Date('2023-04-01');
        $end = new Date('2023-04-30');
        $calendar = new Calendar($start, $end);

        self::assertNull($calendar[new Date('2023-04-03')]);
        $calendar[new Date('2023-04-03')] = 'some data';
        self::assertSame('some data', $calendar[new Date('2023-04-03')]);
    }

    public function testOffsetUnsetIncorrectType(): void
    {
        self::expectException(InvalidArgumentException::class);
        $start = new Date('2023-04-01');
        $calendar = new Calendar($start);

        // @phpstan-ignore-next-line
        unset($calendar[new stdClass()]);
    }

    public function testOffsetUnset(): void
    {
        $start = new Date('2023-04-01');
        $end = new Date('2023-04-30');
        $calendar = new Calendar($start, $end);
        $calendar[new Date('2023-04-03')] = 'some data';

        self::assertTrue(isset($calendar[new Date('2023-04-03')]));
        unset($calendar[new Date('2023-04-03')]);
        self::assertFalse(isset($calendar[new Date('2023-04-03')]));

        self::assertFalse(isset($calendar[new Date('2023-09-03')]));
        unset($calendar[new Date('2023-09-03')]);
        self::assertFalse(isset($calendar[new Date('2023-09-03')]));
    }

    public function testExtendingBeforeStart(): void
    {
        $calendar = new Calendar(new Date('2023-04-01'));

        self::assertFalse(isset($calendar[new Date('2023-03-30')]));
        self::assertFalse(isset($calendar[new Date('2023-03-31')]));
        $calendar->extend(new Date('2023-03-31'));
        self::assertFalse(isset($calendar[new Date('2023-03-30')]));
        self::assertTrue(isset($calendar[new Date('2023-03-31')]));
    }

    public function testExtendingAfterStart(): void
    {
        $calendar = new Calendar(new Date('2023-04-01'));

        self::assertFalse(isset($calendar[new Date('2023-04-02')]));
        self::assertFalse(isset($calendar[new Date('2023-04-03')]));
        $calendar->extend(new Date('2023-04-02'));
        self::assertFalse(isset($calendar[new Date('2023-04-03')]));
        self::assertTrue(isset($calendar[new Date('2023-04-02')]));
    }

    public function testExtendingAlreadyIncluded(): void
    {
        $calendar = new Calendar(new Date('2010-01-01'), new Date('2010-01-31'));
        self::assertCount(31, $calendar);
        $calendar->extend(new Date('2010-01-15'));
        self::assertCount(31, $calendar);
    }
}
