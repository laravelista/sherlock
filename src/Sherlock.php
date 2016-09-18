<?php namespace Laravelista\Sherlock;

class Sherlock
{
    /**
     * Contains deducted data about content.
     *
     * @var array
     */
    protected $library = [];

    /**
     * Contains content that was deducted.
     *
     * @var null
     */
    protected $content = null;

    /**
     * Nothing spectacular,
     * collects empty array into collection.
     *
     */
    public function __construct()
    {
        $this->library = collect($this->library);
    }

    /**
     * Scans given string line by line
     * and remembers chapters.
     *
     * @param  string $content
     * @return [type]
     */
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

    /**
     * Gets deducted data about content.
     *
     * @return array
     */
    public function getLibrary(): array
    {
        return $this->library->toArray();
    }

    /**
     * Returns markdown for requested chapter/s.
     *
     * @param  string|array|null $name
     * @return string
     */
    public function get($name = null): string
    {
        if (is_null($name)) {
            return $this->getContent();
        }

        if (is_array($name)) {
            // TODO: Glue requested chapters together and return
        }

        $chapter = $this->library->where('name', $name)->first();

        return $this->getContent($chapter['starts_at'], $chapter['ends_at']);
    }

    /**
     * Returns markdown content from given line to given line
     * or returns all content.
     *
     * @param  int|null $starts_at
     * @param  int|null $ends_at
     * @return string
     */
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

    /**
     * It determines line number where chapter ends at.
     * Give it a line number on which the chapter starts at.
     *
     * @param  int $line_number
     * @return int
     */
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

    /**
     * Returns boolean if the given line is
     * a chapter/header.
     *
     * @param  string $line
     * @return boolean
     */
    protected function isChapter(string $line): bool
    {
        // Not a chapter for sure.
        if (strlen($line) > 0 and $line[0] !== '#') {
            return false;
        }

        if (strlen($line) < 3) {
            return false;
        }

        // TODO: Needs more checks here.

        return true;
    }

    /**
     * Gets chapter name.
     *
     * @param  string $line
     * @return string
     */
    protected function determineChapterName(string $line): string
    {
        $parts = explode('# ', $line);

        return trim($parts[1]);
    }

    /**
     * Gets chapter level.
     *
     * @param  string $line
     * @return int
     */
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
