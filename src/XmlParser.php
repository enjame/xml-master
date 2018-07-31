<?php

namespace Enjame\Xml;

use phpQuery;

class XmlParser
{
    protected $content;

    public function __construct($content)
    {
        $this->content = $content;
    }


    public function xml2array()
    {
        $result = [];

        $xmlDocument = phpQuery::newDocument($this->content);

        return $this->getElements($xmlDocument);
    }


    public function array2xml()
    {

    }

    protected function getElements($root)
    {
        $phpQueryObject = pq($root);
        $arr = [];
        $result = $phpQueryObject->children();
        $hasChilds = [];
        foreach ($result as $child) {
            if (array_key_exists($child->tagName, $arr)) {
                if (!in_array($child->tagName, $hasChilds)) {
                    $hasChilds[] = $child->tagName;
                    $tmpChild = $arr[$child->tagName];
                    $arr[$child->tagName] = [];
                    $arr[$child->tagName][] = $tmpChild;
                }

                $arr[$child->tagName][] = $this->getItem($child);

            } else {
                $arr[$child->tagName] = $this->getItem($child);
            }
        }
        return $arr;
    }

    protected function getItem($root)
    {
        $childText = preg_replace('/[\\\\\r\n]\s\s+/', '', pq($root)->contents()->eq(0)->text());
        $childAttrs = pq($root)->attr('*');
        $childs = $this->getElements($root);
        $arr = [
            '#value' => $childText,
        ];

        foreach ($childAttrs as $key => $value) {
            $arr['@' . $key] = $value;
        }

        foreach ($childs as $key => $item) {
            $arr[$key] = $item;
        }

        return $arr;
    }
}