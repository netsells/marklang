<?php namespace Netsells\Marklang;

use League\CommonMark\CommonMarkConverter;
use Illuminate\Translation\Translator;
use Illuminate\Routing\UrlGenerator;

class Marklang {

    protected $langPath;
    protected $lang;
    protected $url;
    protected $commonmark;

    protected $routeRegex = '/(?:route:)([a-z.]+)(?:%5B)([a-z-]+(?:%7C)?[a-z-]+)(?:%5D)?/';

    private $paths = [];

    public function __construct(Translator $lang, UrlGenerator $url, $langPath) {
        $this->lang = $lang;
        $this->url = $url;
        $this->langPath = $langPath;

        $this->commonmark = new CommonMarkConverter();
    }

    /**
     * Helper method to allow a nicer looking facade
     * @param $key
     * @return string
     */
    public function trans($key) {
        return $this->contentForKey($key);
    }

    public function contentForKey($key) {
        $markdown = $this->getMarkdownForKey($key);

        if (!$markdown) {
            // TODO :D
        }

        $html = $this->markdownToHtml($markdown);

        return $this->replaceRoutes($html);
    }

    private function currentLocale()
    {
        return $this->lang->locale();
    }

    private function getMarkdownForKey($key)
    {
        $markdownPath = $this->pathForMarkdownFileWithKey($key);
        if (file_exists($markdownPath)) {
            return file_get_contents($markdownPath);
        }
    }

    private function pathForMarkdownFileWithKey($key)
    {
        $keyPath = $this->keyToPath($key);
        return $this->langPath . "/lang/" . $this->currentLocale() . "/content/" . $keyPath . ".md";
    }

    private function keyToPath($key)
    {
        // The performance benefit will be negligible, but still...
        if (!isset($this->paths[$key])) {
            $this->paths[$key] = str_replace('.', '/', trim($key, '.'));
        }

        return $this->paths[$key];
    }

    protected function markdownToHtml($markdown)
    {
        return $this->commonmark->convertToHtml($markdown);
    }

    public function replaceRoutes($html)
    {
        $matchCount = preg_match_all($this->routeRegex, $html, $matches);

        if ($matchCount) {
            foreach($matches[0] as $key => $match) {
                $routeParams = $this->splitParams($matches[2][$key]);
                $html = str_replace($match, $this->url->route($matches[1][$key], $routeParams), $html);
            }
        }

        return $html;
    }

    public function splitParams($paramString)
    {
        return explode('%7C', $paramString);
    }

}
