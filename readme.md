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

Given [this markdown document](https://raw.githubusercontent.com/laravelista/sherlock/0.5.1/sample/document.md) it returns a table of content in [HTML](https://gist.github.com/mabasic/cce935e94a823baa61518d0d4affc92c) unordered list format.

## Features

- get a specific chapter
- get a table of content

## Installation

From console do:

```
composer require laravelista/sherlock
```
