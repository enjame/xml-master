<?php
namespace Enjame\Xml;

class XmlParser
{
    protected $content;

    public function __construct($content)
    {
        $this->content = $content;
    }


    public function xml2array()
    {
        $xml = simplexml_load_string($this->content);
        $json = json_encode($xml);

        return json_decode($json, TRUE);
    }


    public function array2xml()
    {

    }
}