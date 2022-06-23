<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Template;
use voku\helper\HtmlMin;

class MinifyHTML extends Template
{
    /**
     * @param array $data
     * @return string
     */
    public function render(array $data = []): string
    {
        $kirby = Kirby::instance();
        $html = parent::render($data);

        if (
            $kirby->option('afbora.kirby-minify-html.enabled') === true &&
            $this->hasDefaultType() === true
        ) {
            $htmlMin = new HtmlMin();
            $options = $kirby->option('afbora.kirby-minify-html.options', []);

            foreach ($options as $option => $param) {
                if (method_exists($htmlMin, $option) === true) {
                    if ($param !== null) {
                        $htmlMin->{$option}($param);
                    } else {
                        $htmlMin->{$option}();
                    }
                }
            }

            return $htmlMin->minify($html);
        }

        return $html;
    }
}

Kirby::plugin('afbora/kirby-minify-html', [
    'options' => [
        'enabled' => true,
        'options' => []
    ],
    'components' => [
        'template' => function (Kirby $kirby, string $name, string $type = 'html', string $defaultType = 'html') {
            return new MinifyHTML($name, $type, $defaultType);
        }
    ]
]);
