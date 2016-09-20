<?php

namespace spec\Laravelista\Sherlock;

use Laravelista\Sherlock\Sherlock;
use PhpSpec\ObjectBehavior;

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
        $data = include __DIR__ . '/../sample/library.php';

        $this->deduct($this->markdown)
            ->getLibrary()
            ->shouldReturn($data);
    }

    public function it_gets_introduction()
    {
        $response =
            "## Introduction" . PHP_EOL .
            PHP_EOL .
            "In this chapter I am introducing you to this document and its actors." . PHP_EOL;

        $this->deduct($this->markdown)
            ->get('Introduction')
            ->shouldReturn($response);
    }

    public function it_gets_toc()
    {
        /*var_dump($this->deduct($this->markdown)
            ->getToc()->getWrappedObject());*/
        $this->deduct($this->markdown)
            ->getToc()
            ->shouldReturn('<ul class="sherlock-toc"><li><a href="#this-is-the-document-title">This is the document title</a><ul><li><a href="#introduction">Introduction</a><ul><li><a href="#another-introduction">Another introduction</a></li></ul></li><li><a href="#plot">Plot</a></li><li><a href="#conclusion">Conclusion</a></li></ul></li></ul>');
    }
}
