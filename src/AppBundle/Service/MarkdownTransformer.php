<?php


namespace AppBundle\Service;


use Doctrine\Common\Cache\Cache;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class MarkdownTransformer
{
    /**
     * @var MarkdownParserInterface
     */
    private $markdownParser;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * MarkdownTransformer constructor.
     */
    public function __construct(MarkdownParserInterface $markdownParser, Cache $cache)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
    }

    public function parse($str)
    {
        $cache = $this->cache;

        $key = md5($str);
        if ($cache->contains($key)) {
            return $cache->fetch($key);
        }

//        sleep(1); // Uncomment to test cache
        $str = $this->markdownParser
            ->transformMarkdown($str);
        $cache->save($key, $str);
        return $str;
    }
}