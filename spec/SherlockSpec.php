<?php

namespace spec\Laravelista\Sherlock;

use PhpSpec\ObjectBehavior;
use Illuminate\Support\Collection;
use Laravelista\Sherlock\Sherlock;

class SherlockSpec extends ObjectBehavior
{
    protected $markdown;

    public function __construct()
    {
        $this->markdown = file_get_contents(__DIR__ . '/../sample/document.md');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Sherlock::class);
    }

    public function it_should_return_library_as_array()
    {
        $this->getLibrary()
            ->shouldBeArray();

        $this->deduct($this->markdown)
            ->getLibrary()
            ->shouldBeArray();
    }

    public function it_gets_library()
    {
        $data = [
            [
                'level' => 1,
                'name' => 'This is the document title',
                'starts_at' => 0,
                'ends_at' => 3
            ],
            [
                'level' => 2,
                'name' => 'Introduction',
                'starts_at' => 4,
                'ends_at' => 7
            ],
            [
                'level' => 2,
                'name' => 'Plot',
                'starts_at' => 8,
                'ends_at' => 11
            ],
            [
                'level' => 2,
                'name' => 'Conclusion',
                'starts_at' => 12,
                'ends_at' => 14
            ],
        ];

        $this->deduct($this->markdown)
            ->getLibrary()
            ->shouldReturn($data);
    }

    public function it_gets_introduction()
    {
        $response =
        "## Introduction".PHP_EOL.
        PHP_EOL.
        "In this chapter I am introducing you to this document and its actors.".PHP_EOL;

        $this->deduct($this->markdown)
            ->get('Introduction')
            ->shouldReturn($response);
    }
}
