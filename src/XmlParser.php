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

        $xmlDocument = phpQuery::newDocumentXML($this->content);

        return $this->getElements($xmlDocument);
    }


    public function array2xml()
    {

    }

    protected function getElements($root)
    {
        $phpQueryObject = pq($root);
        $result = [];
        $childs = $phpQueryObject->children();
        $hasChilds = [];

        foreach ($childs as $child) {
            if (array_key_exists($child->tagName, $result)) {
                if (!in_array($child->tagName, $hasChilds)) {
                    $hasChilds[] = $child->tagName;
                    $tmpChild = $result[$child->tagName];
                    $result[$child->tagName] = [];
                    $result[$child->tagName][] = $tmpChild;
                }

                $result[$child->tagName][] = $this->getItem($child);

            } else {
                $result[$child->tagName] = $this->getItem($child);
            }
        }

        return $result;
    }

    protected function getItem($object)
    {
        $result = [];

        $value = preg_replace('/[\\\\\r\n]\s\s+/', '', pq($object)->contents()->eq(0)->text());
        $attrs = pq($object)->attr('*');
        $childs = $this->getElements($object);

        if (!empty($value)) {
            $result['#value'] = $value;
        }

        foreach ($attrs as $key => $value) {
            $result['@' . $key] = $value;
        }

        foreach ($childs as $key => $item) {
            $result[$key] = $item;
        }

        return $result;
    }
}