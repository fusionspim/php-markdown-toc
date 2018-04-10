<?php
namespace FusionsTests\PhpMarkdownToc;

use Fusions\PhpMarkdownToc\MarkdownTableOfContents;
use PHPUnit\Framework\TestCase;

class MarkdownTableOfContentsTest extends TestCase
{
    public function testProcess()
    {
        $this->assertSame(
            file_get_contents(__DIR__ . '/../fixtures/sample-toc.md'),
            (new MarkdownTableOfContents(file_get_contents(__DIR__ . '/../fixtures/sample.md')))->process()
        );
    }
}
