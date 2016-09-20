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
        // clear library
        $this->library = collect([]);
        // save content
        $this->content = $content;

        $lines = explode(PHP_EOL, $content);

        $line_number = 0;
        foreach ($lines as $line) {
            $line_number++;
            if ($this->isChapter($line)) {
                $name = $this->deductChapterName($line);
                $this->library->push([
                    'level' => $this->deductChapterLevel($line),
                    'name' => $name,
                    'starts_at' => $line_number - 1,
                    'ends_at' => $this->deductLineNumberWhereChapterEndsAt($line_number),
                    'slug' => $this->createSlug($name),
                ]);
            }
        }

        return $this;
    }

    /**
     * Returns table of content in html format.
     *
     * @return string
     */
    public function getToc()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/templates');
        $twig = new \Twig_Environment($loader);

        return $twig->render('toc.html', ['library' => $this->library]);
    }

    /**
     * Turns "Čista velika" to "cista-velika".
     *
     * @param  string $text
     * @return string
     */
    protected function createSlug(string $text): string
    {
        // Replace croatian characters with normal
        $patterns = ['/č/', '/ć/', '/š/', '/đ/', '/ž/', '/Č/', '/Ć/', '/Š/', '/Đ/', '/Ž/'];
        $replacements = ['c', 'c', 's', 'd', 'z', 'c', 'c', 's', 'd', 'z'];
        $slug = preg_replace($patterns, $replacements, $text);
        // Replace everything except letters, numbers, white space or a dash with empty space
        $slug = trim(preg_replace("/[^a-zA-Z0-9\s-]/", "", $slug));
        // Replace whitespace and a dash with white space and trim the result
        $slug = trim(preg_replace("/[\s-]+/", " ", $slug));
        // Replace whitespace with a dash
        $slug = preg_replace("/\s/", "-", $slug);
        // Convert text to lowercase
        $slug = strtolower($slug);
        return $slug;
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
     * Returns markdown for requested chapter.
     *
     * @param  string $name
     * @return string
     */
    public function get(string $name): string
    {
        $chapter = $this->library->where('name', $name)->first();

        return $this->getContent($chapter['starts_at'], $chapter['ends_at']);
    }

    /**
     * Returns markdown content from given line to given line
     * or returns all content.
     *
     * @param  int $starts_at
     * @param  int $ends_at
     * @return string
     */
    protected function getContent(int $starts_at, int $ends_at): string
    {
        $lines = explode(PHP_EOL, $this->content);

        $content = [];
        for ($i = $starts_at; $i <= $ends_at; $i++) {
            $content[] = $lines[$i];
        }

        return implode(PHP_EOL, $content);
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
     * It deducts line number where chapter ends at.
     * Give it a line number on which the chapter starts at.
     *
     * @param  int $line_number
     * @return int
     */
    protected function deductLineNumberWhereChapterEndsAt(int $line_number): int
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
     * Gets chapter name.
     *
     * @param  string $line
     * @return string
     */
    protected function deductChapterName(string $line): string
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
    protected function deductChapterLevel(string $line): int
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
