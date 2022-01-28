<?php

namespace App\rendering;

use ErrorException;

class Template
{

    private $fileContent;

    function __construct($route)
    {
        global $env;
        $this->name = $route["route"];
        $this->file = $route["twig_path"];
        $this->fileContent = file_get_contents($this->file);
        $this->filePath = "$env[BUILD]/$route[route].html";
        $this->build();
    }

    function getBlocks($file)
    {

        $file = file_get_contents($file);

        $firstBlocks = explode("{% block ", $file);
        $blocks = [];
        foreach ($firstBlocks as $firstBlock) {

            if (count(explode(" %}", $firstBlock)) > 1) {

                $name = explode(" %}", $firstBlock)[0];
                
                $content = explode("{% ", $firstBlock)[0];

                if(endsWith($content, " %}") || strpos($content, " %}") === false){
                    $content = "";
                } else {
                    $content = explode(" %}", $content)[1];
                }

                $block = array(
                    "name" => $name,
                    "content" => $content,
                    "printed" => false
                );
                array_push($blocks, $block);
            }
        }

        array_splice($blocks, 0, 1);
        return $blocks;
    }

    function getLayout()
    {
        global $env;

        $this->layoutFile = explode("{% extends ", $this->fileContent)[1];
        $this->layoutFile = explode(" %}", $this->layoutFile)[0];
        $this->layoutFile = str_replace(["'", '"'], "", $this->layoutFile);
        $this->layoutFile = "$env[TEMPLATES_FOLDER]/$this->layoutFile";
        if (!$this->layoutFile) {
            throw new ErrorException("Incorrect layout file $this->layoutFile", 0, 1, $this->file);
        }

        $this->layoutBlocks = $this->getBlocks($this->layoutFile);

        return $this->layoutBlocks;
    }

    /**
     * Builds template from blocks
     */

    function generateFromBlocks()
    {
        global $env;

        $layout = file_get_contents($this->layoutFile);

        if (!file_exists($this->filePath)) {
            fopen($this->filePath, "w");
        }

        foreach ($this->blocks as $block) {
            foreach ($this->layoutBlocks as $layoutBlock) {
                if ($block["name"] === $layoutBlock["name"]) {

                    $layoutBlockContent = "{% block $layoutBlock[name] %}$layoutBlock[content]{% endblock %}";

                    $content = "$layoutBlock[content]$block[content]";

                    $layout = str_replace($layoutBlockContent, $content, $layout);

                    file_put_contents($this->filePath, $layout);

                    $layoutBlock["printed"] = true;

                    break;
                }
            }
        }
        foreach ($this->layoutBlocks as $layoutBlock) {
            if ($layoutBlock["printed"] === false) {
                $layoutBlockContent = "{% block $layoutBlock[name] %}$layoutBlock[content]{% endblock %}";
                $layout = str_replace($layoutBlockContent, $layoutBlock["content"], $layout);

                file_put_contents($this->filePath, $layout);
            }
        }
    }

    /**
     * Generates HTML file from another one in case user does not extends
     */

    function generate()
    {
        if (!file_exists($this->filePath)) {
            fopen($this->filePath, "w");
        }

        file_put_contents($this->filePath, $this->fileContent);
    }

    public function render()
    {
        include($this->filePath);
    }

    public function build()
    {
        $this->layout = $this->getLayout();
        $this->blocks = $this->getBlocks($this->file);

        if (!empty($this->layout) && !empty($this->blocks)) {
            $this->generateFromBlocks();
        } else {
            $this->generate();
        }
    }
}
