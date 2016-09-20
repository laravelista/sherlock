# Sherlock

[![forthebadge](http://forthebadge.com/images/badges/fuck-it-ship-it.svg)](http://forthebadge.com)
[![forthebadge](http://forthebadge.com/images/badges/no-ragrets.svg)](http://forthebadge.com)

Deduct a markdown document and get a specific chapter and/or a table of content.

It's simple as:

```
$sherlock = new Sherlock;

$sherlock->deduct($markdown)->get('Introduction');

// or

$sherlock->deduct($markdown)->getToc();
```

## Features

- get a specific chapter
- get a table of content

## Installation

From console do:

```
composer require laravelista/sherlock
```

## License

Sherlock is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT)