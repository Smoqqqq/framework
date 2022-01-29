<?php

namespace CascadIO\rendering;

use ErrorException;

class Template
{

    function __construct($route)
    {
        global $env;
        $this->fileContent = file_get_contents($route["twig_path"]);
        $this->filePath = "$env[BUILD]/$route[route].html";
        $this->build();
    }

    /**
     * Builds template from blocks
     */

    function generateFromBlocks()
    {

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

    public function render($route)
    {
        include($this->filePath);
    }

    public function build()
    {
        $this->generate();
    }
}
