<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Toolkit\Tpl;
use Kirby\Cms\Template;
use Kirby\Cms\App as Kirby;

class MinifyHTML extends Template
{
    public function render(array $data = []): string
    {
        $html = Tpl::load($this->file(), $data);

        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );

        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );

        $html = preg_replace($search, $replace, $html);

        return $html;
    }
}

Kirby::plugin('afbora/kirby-minify-html', [
    'components' => [
        'template' => function (Kirby $kirby, string $name, string $contentType = null) {
            return new MinifyHTML($name, $contentType);
        }
    ]
]);