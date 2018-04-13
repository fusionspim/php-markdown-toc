<?php
namespace Fusions\PhpMarkdownToc;

class MarkdownTableOfContents
{
    protected $markdown = '';
    protected $headings = [];
    protected $anchors  = [];

    public function __construct(string $markdown)
    {
        $this->markdown = $markdown;
    }

    // Push the string into a stream for easy iteration.
    // @link: https://evertpot.com/222/
    protected function stringToStream(string $markdown)
    {
        $stream = fopen('php://temp', 'rb+');
        fwrite($stream, $markdown);
        rewind($stream);

        return $stream;
    }

    public function process(): string
    {
        $body = $this->buildLinkedMarkdown();
        $toc  = $this->buildTableOfContents();

        return $toc . "\r\n" . $body;
    }

    protected function buildLinkedMarkdown()
    {
        $markdown = '';
        $stream   = $this->stringToStream($this->markdown);

        while ($line = fgets($stream)) {
            if (
                false !== strpos($line, '#') &&
                preg_match('/^(?P<prespace>\s+)?(?P<level>#{1,6})(?P<title>.*)(?P<postspace>\s+)?$/', $line, $matches) &&
                isset($matches['level'], $matches['title'])
            ) {
                $anchor = $this->getAnchorSlug($matches['title']);

                $this->headings[] = [
                    'level'  => strlen($matches['level']),
                    'title'  => $matches['title'],
                    'anchor' => $anchor,
                ];

                $markdown .= sprintf(
                    '%s%s <a name="%s" id="%s">%s</a>%s',
                    $matches['prespace'] ?? '',
                    $matches['level'],
                    $anchor,
                    $anchor,
                    trim($matches['title']),
                    $matches['postspace'] ?? ''
                );
            } else {
                $markdown .= $line;
            }
        }

        return $markdown;
    }

    protected function getAnchorSlug(string $string): string
    {
        $anchor = str_slug(trim($string));

        if (isset($this->anchors[$anchor])) {
            $this->anchors[$anchor] = ($this->anchors[$anchor] + 1);
            $anchor .= '-' . $this->anchors[$anchor];
        } else {
            $this->anchors[$anchor] = 1;
        }

        return $anchor;
    }

    protected function buildTableOfContents(): string
    {
        if (count($this->headings) === 0) {
            return '';
        }

        $markdown = '# Contents' . "\n";

        foreach ($this->headings as $heading) {
            $markdown .= sprintf(
                '%s [%s](#%s)' . "\n",
                str_repeat('    ', $heading['level'] - 1) . '*',
                trim($heading['title']),
                $heading['anchor']
            );
        }

        return $markdown;
    }
}
