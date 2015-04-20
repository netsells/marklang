<?php namespace Netsells\Marklang;

use League\CommonMark\CommonMarkConverter;

class Marklang {

    public function trans($key) {
        return $this->contentForKey($key);
    }

    public function contentForKey($key) {
        $currentLang = \Lang::locale();
        $path = app_path("lang/{$currentLang}/content/" . str_replace('.', '/', $key) . ".md");

        $converter = new CommonMarkConverter();
        $contents = file_get_contents($path);

        $html = $converter->convertToHtml($contents);

        return $this->replaceRoutes($html);
    }

    public function replaceRoutes($html)
    {
        $matchCount = preg_match_all('/(?:route:)([a-z.]+)(?:%5B)([a-z-]+(?:%7C)?[a-z-]+)(?:%5D)?/', $html, $matches);

        if ($matchCount) {
            foreach($matches[0] as $key => $match) {
                $routeParams = $this->splitParams($matches[2][$key]);
                $html = str_replace($match, route($matches[1][$key], $routeParams), $html);
            }
        }

        return $html;
    }

    public function splitParams($paramString)
    {
        return explode('%7C', $paramString);
    }

}
