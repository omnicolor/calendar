# Calendar

This package allows storing arbitrary data attached to a date. In my particular use case, I wanted to be able to store information about what's going on in a role playing game, to allow rendering a rich history of the game so far.

## Usage
This example uses stdClass to represent arbitrary data, but could be a PHP class. Storing it in the database could either be done through converting the object to a JSON representation or serializing it, whichever works best for your particular use case.

```php
<?php

declare(strict_types=1);

use Carbon\CarbonImmutable as Date;
use Omnicolor\Calendar\Calendar;

// Create a calendar for January 2080.
$calendar = new Calendar(new Date('2080-01-01'), new Date('2080-01-31'));

// Add some random journal entries.
$calendar[new Date('2080-01-01')] = (object)[
    'description' => 'Feeling rather tired after the New Years party.',
    'mood' => 3,
];
$calendar[new Date('2080-01-02')] = (object)[
    'description' => 'Starting the new year off with a job opportunity. '
        . 'The pay looks promising.',
    'mood' => 10,
    'potentialPay' => 20000,
];
$calendar[new Date('2080-01-08')] = (object)[
    'description' => 'Should have known there would be a double-cross. '
        . 'Not only did we not get paid, but we lost three hundred!',
    'mood' => 1,
    'pay' => -300,
];

foreach ($calendar as $date => $entry) {
    // Render calendar however you see fit. If a $date
    // doesn't have an entry, $entry will be null.
}
```

## Rendering
If you'd like to render a plain calendar on a web page, there's an example decorator that will wrap just the calendar's dates in \<div\> tags that you can style as you see fit. It will generate something like:

```html
<div class="calendar">
    <div class="week">
        <div class="day">1</div>
        <div class="day">2</div>
        <div class="day">3</div>
        <div class="day">4</div>
        <div class="day">5</div>
        <div class="day">6</div>
        <div class="day">7</div>
    </div>
    ...
</div>
```
If you'd like to render the calendar's entry data, you'll need to implement something similar to `BasicHtmlDecorator` that handles your particular data.

## Tests
You can run the unit tests for the calendar with `composer test`, or if you'd like to view a code coverage report, `composer coverage`. Code style (`composer cs-fix`) and static analysis (`composer phpstan`) are also available. `composer all` will run tests with coverage, code style, and static analysis.