<?php namespace Laravelista\Sherlock;

class Sherlock
{
    protected $library = [];
    protected $content = null;

    public function __construct()
    {
        $this->library = collect($this->library);
    }

    public function deduct(string $content)
    {
        $this->content = $content;

        $lines = explode(PHP_EOL, $content);

        $line_number = 0;
        foreach ($lines as $line) {
            $line_number++;
            if ($this->isChapter($line)) {
                $this->library->push([
                    'level' => $this->determineChapterLevel($line),
                    'name' => $this->determineChapterName($line),
                    'starts_at' => $line_number - 1,
                    'ends_at' => $this->determineLineNumberWhereChapterEndsAt($line_number),
                ]);
            }
        }

        return $this;
    }

    public function getLibrary()
    {
        return $this->library;
    }

    public function get(string $name = null): string
    {
        if (is_null($name)) {
            return $this->getContent();
        }

        $chapter = $this->library->where('name', $name)->first();

        return $this->getContent($chapter['starts_at'], $chapter['ends_at']);
    }

    public function getContent(int $starts_at = null, int $ends_at = null): string
    {
        if (is_null($starts_at)) {
            return $this->content;
        }

        $lines = explode(PHP_EOL, $this->content);

        $content = [];
        for ($i = $starts_at; $i <= $ends_at; $i++) {
            $content[] = $lines[$i];
        }

        return implode(PHP_EOL, $content);
    }

    protected function determineLineNumberWhereChapterEndsAt(int $line_number): int
    {
        $lines = explode(PHP_EOL, $this->content);

        for ($i = $line_number; $i < count($lines); $i++) {
            if ($this->isChapter($lines[$i])) {
                return $i - 1;
            }
        }

        return count($lines) - 1;
    }

    protected function isChapter(string $line): bool
    {
        // Not a chapter for sure.
        if ($line[0] !== '#') {
            return false;
        }

        return true;

    }

    protected function determineChapterName(string $line): string
    {
        $parts = explode('# ', $line);

        return trim($parts[1]);
    }

    protected function determineChapterLevel(string $line): int
    {
        $counter = 0;

        for ($i = 0; $i < strlen($line); $i++) {
            if ($line[$i] === '#') {
                $counter++;
                continue;
            }

            if ($line[$i] === ' ') {
                if ($line[$i - 1] === '#') {
                    break;
                }
                $counter = 0;
                break;
            }
        }

        return $counter;
    }
}
