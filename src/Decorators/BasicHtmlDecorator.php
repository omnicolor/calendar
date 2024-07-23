<?php

declare(strict_types=1);

namespace Omnicolor\Calendar\Decorators;

use Carbon\CarbonImmutable as Date;
use Omnicolor\Calendar\Calendar;
use OutOfBoundsException;

/**
 * @psalm-suppress UnusedClass
 */
class BasicHtmlDecorator implements DecoratorInterface
{
    public function __construct(protected Calendar $calendar, protected Date $date)
    {
        if ($date < $calendar->getStart() || $date > $calendar->getEnd()) {
            throw new OutOfBoundsException();
        }
    }

    public function __toString(): string
    {
        $daysInMonth = $this->date->daysInMonth;
        $start = $this->date->day(1);
        $end = $this->date->day($daysInMonth);
        $html = '<div class="month"><div class="week">';

        // Prepend days before the first.
        for ($i = 0; $i < $start->dayOfWeek; $i++) {
            $html .= '<div class="day"></div>';
        }

        /** @var mixed $entry */
        foreach ($this->calendar as $date => $entry) {
            $html .= '<div class="day">' . $date->day;
            if (null !== $entry) {
                $html .= '*';
            }
            $html .= '</div>';
            if (6 === $date->dayOfWeek && $date->day < $daysInMonth) {
                $html .= '</div><div class="week">';
            }
        }

        for ($i = $end->dayOfWeek; $i < 6; $i++) {
            $html .= '<div class="day"></div>';
        }

        return $html . '</div></div>';
    }
}
