<?php

namespace Traits;

/**
 * Trait TraitUrlParser
 * @package Src\Traits
 */
trait TraitUrlParser
{
    /**
     * Retorna a url requisitada delimitada por '/' em um array.
     * @return array
     */
    final public function parseUrl(): array
    {
        return explode('/', rtrim($_GET['url']),FILTER_SANITIZE_URL);
    } // parseUrl
} // TraitUrlParser