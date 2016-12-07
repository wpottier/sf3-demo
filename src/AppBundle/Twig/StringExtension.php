<?php

namespace AppBundle\Twig;

class StringExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('substring', [$this, 'substring']),
            new \Twig_SimpleFilter('base64encode', 'base64_encode'),
            new \Twig_SimpleFilter('rtrim', 'rtrim'),
            new \Twig_SimpleFilter('ltrim', 'ltrim'),
        ];
    }

    public function substring($value, $start, $length)
    {
        return substr($value, $start, $length);
    }
}