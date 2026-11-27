<?php

namespace App\Services\Markdown;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * Small, dependency-free HTML -> Markdown converter.
 *
 * It intentionally supports only the markup used by the site's content
 * editors (headings, paragraphs, lists, links, images, quotes and code).
 * No Composer package is needed for the Markdown endpoints.
 */
final class HtmlToMarkdown
{
    /** Elements that never belong in a readable text representation. */
    private const SKIPPED = [
        'script', 'style', 'noscript', 'svg', 'iframe', 'form', 'button',
        'nav', 'aside', 'footer', 'header', 'video', 'audio', 'select',
    ];

    public function convert(?string $html): string
    {
        if (trim((string) $html) === '') {
            return '';
        }

        $document = $this->document((string) $html);
        $root = $document->getElementById('markdown-root');

        if (! $root) {
            return '';
        }

        return $this->clean($this->children($root));
    }

    /**
     * Extract the readable part of a fully rendered Blade page.
     * The public layout wraps page content in <main>.
     */
    public function mainContent(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $document = $this->document($html);
        $main = $document->getElementsByTagName('main')->item(0);

        return $main ? $this->innerHtml($main) : $html;
    }

    private function document(string $html): DOMDocument
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="markdown-root">'.$html.'</div>',
            LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $document;
    }

    private function children(DOMNode $node): string
    {
        $output = '';

        foreach ($node->childNodes as $child) {
            $output .= $this->node($child);
        }

        return $output;
    }

    private function node(DOMNode $node): string
    {
        if ($node instanceof DOMText) {
            $text = $node->textContent;

            return in_array($node->parentNode?->nodeName, ['pre', 'code'], true)
                ? $text
                : $this->escape($text);
        }

        if (! $node instanceof DOMElement) {
            return '';
        }

        $tag = strtolower($node->nodeName);

        if ($node->getAttribute('aria-hidden') === 'true' || in_array($tag, self::SKIPPED, true)) {
            return '';
        }

        if ($tag === 'details') {
            return $this->details($node);
        }

        if (preg_match('/^h([1-6])$/', $tag, $matches)) {
            return str_repeat('#', (int) $matches[1]).' '.$this->inline($node)."\n\n";
        }

        if ($tag === 'br') {
            return "\n";
        }

        if ($tag === 'hr') {
            return "---\n\n";
        }

        if ($tag === 'p') {
            return $this->inline($node)."\n\n";
        }

        if ($tag === 'strong' || $tag === 'b') {
            return '**'.$this->inline($node).'**';
        }

        if ($tag === 'em' || $tag === 'i') {
            return '*'.$this->inline($node).'*';
        }

        if ($tag === 'del' || $tag === 's') {
            return '~~'.$this->inline($node).'~~';
        }

        if ($tag === 'code') {
            return '`'.trim($this->nodeText($node)).'`';
        }

        if ($tag === 'pre') {
            return "\n```\n".trim($this->nodeText($node))."\n```\n\n";
        }

        if ($tag === 'a') {
            $href = trim($node->getAttribute('href'));
            $label = trim($this->inline($node));

            if ($href === '' || $label === '' || str_starts_with($href, 'javascript:')) {
                return $label;
            }

            return '['.$label.']('.$href.')';
        }

        if ($tag === 'img') {
            $src = trim($node->getAttribute('src'));
            $alt = trim($this->escape($node->getAttribute('alt')));

            return $src === '' ? '' : '!['.$alt.']('.$src.')'."\n\n";
        }

        if ($tag === 'ul' || $tag === 'ol') {
            return $this->list($node, $tag === 'ol');
        }

        if ($tag === 'blockquote') {
            $quoted = trim($this->children($node));

            return implode("\n", array_map(
                fn (string $line) => '> '.$line,
                explode("\n", $quoted)
            ))."\n\n";
        }

        if ($tag === 'table') {
            return $this->table($node);
        }

        // Structural containers add no Markdown of their own; their children
        // (paragraphs, headings, lists, ...) provide the separation.
        return $this->children($node);
    }

    /** FAQ accordions become a question heading followed by the answer. */
    private function details(DOMElement $node): string
    {
        $summary = null;
        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement && strtolower($child->nodeName) === 'summary') {
                $summary = $child;
                break;
            }
        }

        $question = $summary ? trim($this->inline($summary)) : '';
        $answer = '';

        foreach ($node->childNodes as $child) {
            if ($child === $summary) {
                continue;
            }
            $answer .= $this->node($child);
        }

        return ($question !== '' ? '### '.$question."\n\n" : '').trim($answer)."\n\n";
    }

    private function inline(DOMElement $node): string
    {
        return $this->children($node);
    }

    private function nodeText(DOMNode $node): string
    {
        return $node->textContent;
    }

    private function list(DOMElement $node, bool $ordered): string
    {
        $lines = '';
        $position = 1;

        foreach ($node->childNodes as $child) {
            if (! $child instanceof DOMElement || strtolower($child->nodeName) !== 'li') {
                continue;
            }

            $marker = $ordered ? $position++.'.' : '-';
            $content = trim($this->children($child));
            $content = str_replace("\n", "\n  ", $content);
            $lines .= $marker.' '.$content."\n";
        }

        return $lines."\n";
    }

    private function table(DOMElement $node): string
    {
        $rows = [];

        foreach ($node->getElementsByTagName('tr') as $row) {
            $cells = [];
            foreach ($row->childNodes as $cell) {
                if ($cell instanceof DOMElement && in_array(strtolower($cell->nodeName), ['td', 'th'], true)) {
                    $cells[] = trim(preg_replace('/\s+/u', ' ', $this->children($cell)) ?? '');
                }
            }
            if ($cells !== []) {
                $rows[] = $cells;
            }
        }

        if ($rows === []) {
            return '';
        }

        $width = max(array_map('count', $rows));
        $rows = array_map(fn (array $row) => array_pad($row, $width, ''), $rows);
        $output = '| '.implode(' | ', array_shift($rows))." |\n";
        $output .= '| '.implode(' | ', array_fill(0, $width, '---'))." |\n";

        foreach ($rows as $row) {
            $output .= '| '.implode(' | ', $row)." |\n";
        }

        return $output."\n";
    }

    private function escape(string $text): string
    {
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return str_replace(
            ['\\', '*', '_', '[', ']'],
            ['\\\\', '\\*', '\\_', '\\[', '\\]'],
            $text
        );
    }

    private function clean(string $markdown): string
    {
        $markdown = preg_replace("/[ \t]+\n/", "\n", $markdown) ?? $markdown;
        $markdown = preg_replace("/\n{3,}/", "\n\n", $markdown) ?? $markdown;

        return trim($markdown)."\n";
    }

    private function innerHtml(DOMNode $node): string
    {
        $html = '';

        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument?->saveHTML($child) ?? '';
        }

        return $html;
    }
}
