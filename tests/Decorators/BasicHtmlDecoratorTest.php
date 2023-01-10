<?php

declare(strict_types=1);

namespace Omnicolor\Calendar\Tests\Decorators;

use Carbon\CarbonImmutable as Date;
use Omnicolor\Calendar\Calendar;
use Omnicolor\Calendar\Decorators\BasicHtmlDecorator;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class BasicHtmlDecoratorTest extends TestCase
{
    public function testEmptyCalendar(): void
    {
        $calendar = new Calendar(new Date('2023-02-01'), new Date('2023-02-28'));
        $decorator = new BasicHtmlDecorator($calendar, new Date('2023-02-10'));
        $expected = '<div class="month">'
            . '<div class="week">'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '<div class="day">1</div>'
            . '<div class="day">2</div>'
            . '<div class="day">3</div>'
            . '<div class="day">4</div>'
            . '</div>'
            . '<div class="week">'
            . '<div class="day">5</div>'
            . '<div class="day">6</div>'
            . '<div class="day">7</div>'
            . '<div class="day">8</div>'
            . '<div class="day">9</div>'
            . '<div class="day">10</div>'
            . '<div class="day">11</div>'
            . '</div>'
            . '<div class="week">'
            . '<div class="day">12</div>'
            . '<div class="day">13</div>'
            . '<div class="day">14</div>'
            . '<div class="day">15</div>'
            . '<div class="day">16</div>'
            . '<div class="day">17</div>'
            . '<div class="day">18</div>'
            . '</div>'
            . '<div class="week">'
            . '<div class="day">19</div>'
            . '<div class="day">20</div>'
            . '<div class="day">21</div>'
            . '<div class="day">22</div>'
            . '<div class="day">23</div>'
            . '<div class="day">24</div>'
            . '<div class="day">25</div>'
            . '</div>'
            . '<div class="week">'
            . '<div class="day">26</div>'
            . '<div class="day">27</div>'
            . '<div class="day">28</div>'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '</div>'
            . '</div>';
        self::assertSame($expected, (string)$decorator);
    }

    public function testCalendarEndingOnSaturday(): void
    {
        $calendar = new Calendar(new Date('2023-09-01'), new Date('2023-09-30'));
        $decorator = new BasicHtmlDecorator($calendar, new Date('2023-09-10'));
        self::assertStringNotContainsString(
            '</div></div><div class="week"></div></div>',
            (string)$decorator
        );
    }

    public function testPartialCalendar(): void
    {
        $calendar = new Calendar(new Date('2023-01-01'), new Date('2023-01-05'));
        $decorator = new BasicHtmlDecorator($calendar, new Date('2023-01-01'));
        $expected = '<div class="month">'
            . '<div class="week">'
            . '<div class="day">1</div>'
            . '<div class="day">2</div>'
            . '<div class="day">3</div>'
            . '<div class="day">4</div>'
            . '<div class="day">5</div>'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '<div class="day"></div>'
            . '</div>'
            . '</div>';
        self::assertSame($expected, (string)$decorator);
    }

    public function testRenderCalenderWithDateEarly(): void
    {
        self::expectException(OutOfBoundsException::class);
        $calendar = new Calendar(new Date('2023-01-01'), new Date('2023-01-05'));
        new BasicHtmlDecorator($calendar, new Date('2018-01-01'));
    }

    public function testRenderCalendarWithDateLate(): void
    {
        self::expectException(OutOfBoundsException::class);
        $calendar = new Calendar(new Date('2023-01-01'), new Date('2023-01-05'));
        new BasicHtmlDecorator($calendar, new Date('2023-01-06'));
    }
}
