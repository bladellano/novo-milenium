<?php

namespace Sts;

use Rain\Tpl;

class Page
{

    private $tpl;
    private $options = [];
    private $defaults = [
        "header"=>true,
        "footer"=>true,
        "data" => [],
    ];

    public function __construct($opts = array(),$tpl_dir="/views/")
    {
        $this->options = array_merge($this->defaults, $opts);
        $config = array(
            "tpl_dir" => $_SERVER["DOCUMENT_ROOT"] . $tpl_dir,
            "cache_dir" => $_SERVER["DOCUMENT_ROOT"] . "/views-cache/",
            "debug" => false,
        );

        Tpl::configure($config);
        $this->tpl = new Tpl;
        
        $this->setData($this->options["data"]);       
         if($this->options["header"]===true) $this->tpl->draw("header");

    }

    private function setData($data = array()){
        foreach ($data as $key => $value) {
            $this->tpl->assign($key, $value);
        }
    }
    public function setTpl($name, $data = array(), $returnHTML = false)
    {
        if(!file_exists($_SERVER["DOCUMENT_ROOT"].'/views/'.$name.'.html'))
            $name = 'not-found';

        $this->setData($data);
        return $this->tpl->draw($name,$returnHTML);
    }

    public function __destruct()
    {
        if($this->options["header"]===true) $this->tpl->draw("footer");
    }

}