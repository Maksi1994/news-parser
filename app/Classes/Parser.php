<?php

namespace App\Classes;

use Sunra\PhpSimple\HtmlDomParser;

class Parser
{

    protected function fetchPage($url)
    {
        $html_text = HtmlDomParser::file_get_html($url, false, null, 0);
        return  HtmlDomParser::str_get_html($html_text);
    }
}
