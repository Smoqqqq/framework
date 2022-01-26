<?php

namespace App\rendering;

use ErrorException;

class Template
{

    private $fileContent;

    function __construct($name, $file)
    {
        global $env;
        $this->name = $name;
        $this->file = "$env[TEMPLATES_FOLDER]/$file";
        $this->fileContent = file_get_contents($this->file);
        $this->build();
    }

    function getBlocks($file)
    {

        $file = file_get_contents($file);

        $firstBlocks = explode("{% block ", $file);
        $blocks = [];
        foreach ($firstBlocks as $firstBlock) {
            $content = explode("{% ", $firstBlock)[0];
            $content = explode(" %}", $content)[1];

            $name = explode(" %}", $firstBlock)[0];

            $block = array(
                "name" => $name,
                "content" => $content,
                "printed" => false
            );
            array_push($blocks, $block);
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

        $this->filePath = "$env[BUILD]/$this->name.html";
        $layout = file_get_contents($this->layoutFile);

        if (!file_exists($this->filePath)) {
            fopen($this->filePath, "w");
        }

        foreach ($this->blocks as $block) {
            foreach ($this->layoutBlocks as $layoutBlock) {
                if ($block["name"] === $layoutBlock["name"]) {

                    $layoutBlockContent = "{% block $layoutBlock[name] %}$layoutBlock[content]{% endblock %}";

                    $layout = str_replace($layoutBlockContent, $block["content"], $layout);

                    file_put_contents($this->filePath, $layout);

                    $layoutBlock["printed"] = true;

                    break;
                }
            }
        }
        foreach ($this->layoutBlocks as $layoutBlock) {
            if ($layoutBlock["printed"] === false) {
                $layoutBlockContent = "{% block $layoutBlock[name] %}$layoutBlock[content]{% endblock %}";
                $layout = str_replace($layoutBlockContent, "", $layout);

                file_put_contents($this->filePath, $layout);
            }
        }
    }

    /**
     * Generates HTML file from another one in case user does not extends
     */

    function generate()
    {
        global $env;

        $this->filePath = "$env[BUILD]/$this->name.html";
        $layout = file_get_contents($this->layoutFile);

        if (!file_exists($this->filePath)) {
            fopen($this->filePath, "w");
        }

        file_put_contents($this->filePath, $this->fileContent);
    }

    public function render()
    {
        global $env;
        $env["RENDERED_PAGE"] = $this->filePath;
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
