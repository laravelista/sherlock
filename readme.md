# Sherlock

[![Latest Stable Version](https://poser.pugx.org/laravelista/sherlock/v/stable)](https://packagist.org/packages/laravelista/sherlock)
[![Total Downloads](https://poser.pugx.org/laravelista/sherlock/downloads)](https://packagist.org/packages/laravelista/sherlock)
[![License](https://poser.pugx.org/laravelista/sherlock/license)](https://packagist.org/packages/laravelista/sherlock)
[![Build Status](https://travis-ci.org/laravelista/sherlock.svg?branch=master)](https://travis-ci.org/laravelista/sherlock)

[![forthebadge](http://forthebadge.com/images/badges/fuck-it-ship-it.svg)](http://forthebadge.com)
[![forthebadge](http://forthebadge.com/images/badges/no-ragrets.svg)](http://forthebadge.com)

Sherlock is a PHP package that provides unique features for markdown.

It can create a Table of Content or retrieve a specific chapter.

## Overview

I use this package on my website [Laravelista](https://laravelista.com) to create table of content for my lessons, posts and packages. Also, I use it to provide free samples of my lessons.

### Get the Table of Content

Given this markdown:

```markdown
# Book Title

## Chapter 1

This is chapter *one*.

## Chapter 2

This is chapter two.
```

You can generate a table of content:

```php
use Laravelista\Sherlock\Sherlock;

$sherlock = new Sherlock;

return $sherlock->deduct($markdown)->getToc();
```

**HTML Output:**

```html
<ul class="sherlock-toc">
    <li><a href="#book-title">Book Title</a>
        <ul>
            <li><a href="#chapter-1">Chapter 1</a></li>
            <li><a href="#chapter-2">Chapter 2</a></li>
        </ul>
    </li>
</ul>
```

### Get a specific chapter

Given the same markdown as in the sample above we can fetch a specific chapter from our markdown documents by its name.

```php
use Laravelista\Sherlock\Sherlock;

$sherlock = new Sherlock;

return $sherlock->deduct($markdown)->get('Chapter 1');
```

**Markdown output:**

```markdown
This is chapter *one*.
```

### Laravel usage inside views

If you are using Laravel, there is a convenient way of loading Sherlock in your views. At the top of your view file where you want to display the Table of Content add this code to inject Sherlock and deduct the content:

```html
@inject('sherlock', 'Laravelista\Sherlock\Sherlock')
<?php $sherlock->deduct($lesson->content) ?>
```

And then in the place where you want to display the actual Table of Content add this:

```html
{!! $sherlock->getToc() !!}
```

or to get a specific chapter use `$sherlock->get()`. Just remember that `get()` returns markdown, so be sure to parse the markdown to HTML.

## Installation

From the command line:

```
composer require laravelista/sherlock
```

## API

### deduct

Reads given markdown string and generates an index of the document (Library).

```php
$sherlock->deduct(string $content)
```

You can chain this method with other methods from the API, but this method must always be called first. Library can be retrieved if needed with `getLibrary()`.

### getToc

Returns the Table of Content in HTML format.

```
$sherlock->deduct($markdown)->getToc()
```

**Example:**

Given this markdown:

```markdown
# Book Title

## Chapter 1

This is chapter *one*.

## Chapter 2

This is chapter two.
```

it returns this HTML output:

```html
<ul class="sherlock-toc">
    <li><a href="#book-title">Book Title</a>
        <ul>
            <li><a href="#chapter-1">Chapter 1</a></li>
            <li><a href="#chapter-2">Chapter 2</a></li>
        </ul>
    </li>
</ul>
```

### get

Returns markdown for specific chapter.

```php
$sherlock->deduct($markdown)->get('Chapter 1')
```

**Example:**

Given this markdown:

```markdown
# Book Title

## Chapter 1

This is chapter *one*.

## Chapter 2

This is chapter two.
```

it returns this Markdown output:

```markdown
This is chapter *one*.
```

### getLibrary

It returns the index of the document (Library); which was deducted from the given markdown in `deduct()` method; as an array.

```php
$sherlock->deduct($markdown)->getLibrary()
```

**Example:**

This is a sample of the Library you get:

```php
[
    [
        'level' => 1,
        'name' => 'This is the document title',
        'starts_at' => 0,
        'ends_at' => 3,
        'slug' => 'this-is-the-document-title'
    ],
    [
        'level' => 2,
        'name' => 'Introduction',
        'starts_at' => 4,
        'ends_at' => 7,
        'slug' => 'introduction'
    ],
    [
        'level' => 3,
        'name' => 'Another introduction',
        'starts_at' => 8,
        'ends_at' => 11,
        'slug' => 'another-introduction'
    ],
    [
        'level' => 4,
        'name' => 'Deep introduction',
        'starts_at' => 12,
        'ends_at' => 19,
        'slug' => 'deep-introduction'
    ],
    [
        'level' => 2,
        'name' => 'Plot',
        'starts_at' => 20,
        'ends_at' => 23,
        'slug' => 'plot'
    ],
    [
        'level' => 2,
        'name' => 'Conclusion',
        'starts_at' => 24,
        'ends_at' => 27,
        'slug' => 'conclusion'
    ],
];
```

With it you can do all sorts of things. 
