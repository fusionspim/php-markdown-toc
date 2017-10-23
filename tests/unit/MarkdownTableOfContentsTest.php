<?php
namespace FusionsTests\PhpMarkdownToc;

use Fusions\PhpMarkdownToc\MarkdownTableOfContents;
use PHPUnit\Framework\TestCase;

class MarkdownTableOfContentsTest extends TestCase
{

    public function testProcess()
    {
        $actualMarkdown   = file_get_contents(TEST_FIXTURE_DIR . 'markdown/sample.md');
        $expectedMarkdown = file_get_contents(TEST_FIXTURE_DIR . 'markdown/sample-toc.md');

        file_put_contents(
            TEST_OUTPUT_DIR . 'markdown/sample-toc.md',
            (new MarkdownTableOfContents($actualMarkdown))->process()
        );

        $this->assertEquals(
            $expectedMarkdown,
            file_get_contents(TEST_OUTPUT_DIR . 'markdown/sample-toc.md')
        );
    }
}
