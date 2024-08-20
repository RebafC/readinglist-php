<?php

declare(strict_types=1);

namespace App;

use \Twig\TwigTest;
use \Twig\TwigFilter;
use \Twig\Extension\AbstractExtension;

/*
    hybrid from
    https://processwire.dev/twig-processwire-custom-functionality/
    https://dev.to/yanyy/write-your-own-filters-and-functions-in-twig-2epn
    https://symfony.com/doc/5.3/templating/twig_extension.html

    other things found at
    https://twig.symfony.com/doc/3.x/templates.html#whitespace-control
    {{- No_leading_trailing_whitespace_or_newlines -}} {# Removes all whitespace (including newlines) #}
    {{~ No_leading_trailing_whitespace ~}}             {# Removes all whitespace (excluding newlines) #}
    {% apply spaceless %}{% endapply %}                {# Removes spaces between HTML tags #}

*/
class TwigUtilities extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('truncate', [$this, 'truncate']),
            new TwigFilter('newlinesToSpace', [$this, 'newlinesToSpace']),
            new TwigFilter('highlightTermInText', [$this, 'highlightTermInText']),
        ];
    }

    public function truncate(
        string $text,
        int $limit,
        ?string $ellipsis = ' …',
        bool $strip_tags = false
    ): string {
        if ($strip_tags) {
            $text = strip_tags($text);
        }

        if (strlen($text) > $limit) {
            $ell_length = $ellipsis ? strlen($ellipsis) : 0;
            $append = $ellipsis ?? '';
            $text = substr($text, 0, $limit - ($ell_length + 1)) . $append;
        }

        return $text;
    }

    /**
     * Convert all consecutive newlines into a single space character.
     */
    public function newlinesToSpace(string $text): string
    {
        return preg_replace(
            '/[\r\n]+/',
            ' ',
            $text
        );
    }

    public function highlightTermInText(
        string $text,
        string $term,
        string $highlightElement = 'mark',
        array $highlightElementClasses = [],
        bool $caseSensitive = false
    ): string {
        $classString = implode(" ", $highlightElementClasses);
        $startTag = sprintf('<%s class="%s">', $highlightElement, $classString);
        $endTag = sprintf('</%s>', $highlightElement);
        return preg_replace_callback(
            '/' . preg_quote($term, '/') . '/' . ($caseSensitive ? '' : 'i'),
            static fn($matches): string => $startTag . $matches[0] . $endTag,
            $text
        );
    }
}
