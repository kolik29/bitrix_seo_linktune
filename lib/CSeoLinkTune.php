<?php
namespace SeoLinkTune;

class CSeoLinkTune
{    
    /**
     * onEndBufferContentHandler
     *
     * @param  mixed $content
     * @return void
     */
    public static function onEndBufferContentHandler(string &$content): void
    {
        global $DB;

        $result = $DB->Query('SELECT TITLE, DESCRIPTION, CANONICAL FROM b_seo_linktune_links WHERE NAME = "' . $_SERVER['REQUEST_URI'] . '"');

        while ($arElement = $result->Fetch()) {
            if (!empty($arElement['TITLE'])) {
                $content = static::changeTag('title', $arElement['TITLE'], $content);
            }
            
            if (!empty($arElement['DESCRIPTION'])) {
                $content = static::changeTag('description', $arElement['DESCRIPTION'], $content);
            }

            if (!empty($arElement['CANONICAL'])) {
                $content = static::changeTag('canonical', $arElement['CANONICAL'], $content);
            }
        }
    }
    
    /**
     * changeTag
     *
     * @param  mixed $tag
     * @param  mixed $val
     * @param  mixed $head
     * @return string
     */
    private static function changeTag(string $tag, string $val, string $head): string
    {
        if ($tag == 'title') {
            $tagPattern = '/<title>.*<\/title>/';
            $val = '<title>' . $val . '</title>';

            $head = static::tagReplace($tagPattern, $val, $head);
        }

        if ($tag == 'description') {
            $tagPattern = '/<meta\s+name="description"\s+content="([^"]*)"/';
            $val = '<meta name="description" content="' . $val . '"';

            $head = static::tagReplace($tagPattern, $val, $head);
        }

        if ($tag == 'canonical') {
            $tagPattern = '/<link\s+rel="canonical"\s+href="([^"]*)"/';
            $val = '<link rel="canonical" href="' . $val . '"';

            $head = static::tagReplace($tagPattern, $val, $head);
        }

        return $head;
    }
    
    /**
     * tagReplace
     *
     * @param  mixed $tagPattern
     * @param  mixed $val
     * @param  mixed $head
     * @return string
     */
    private static function tagReplace(string $tagPattern, string $val, string $head): string
    {
        if (preg_match($tagPattern, $head)) {
            $head = preg_replace($tagPattern, $val, $head);
        } else {
            $head = $val . ' />' . $head;
        }

        return $head;
    }
}
