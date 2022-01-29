<?php

namespace CascadIO\rendering;

use ErrorException;

class Twig extends Template
{

    public function render($route = null)
    {
        global $env;

        if (gettype($route) === "string") {
            foreach ($this->routes as $route1) {
                if ($route1["twig_path"] === "$env[TEMPLATES_FOLDER]/$route") {
                    $route = $route1;
                    break;
                }
            }
        }

        if ($route === null) $route = $this->currentRoute;

        $this->name = $route["route"];
        $this->file = $route["twig_path"];
        $this->fileContent = file_get_contents($route["twig_path"]);
        $this->filePath = "$env[BUILD]/$route[route].html";
        $this->build();
        include($this->filePath);
    }

    function getBlocks($file)
    {
        $blocks = [];

        $this->knownBlocksTypes = array(
            array("first_delimiter" => "{% block ",     "second_delimiter" => " %}",        "name" => "block",      "end" => "{% endblock %}",  "has_name" => true),
            array("first_delimiter" => "{% extends \"", "second_delimiter" => "\" %}",      "name" => "extends",    "end" => " %}",             "has_name" => true),
            array("first_delimiter" => "{% include \"", "second_delimiter" => "\" %}",      "name" => "include",    "end" => " %}",             "has_name" => true),
            array("first_delimiter" => "{% if ",        "second_delimiter" => " %}",        "name" => "if",         "end" => "{% endif %}",     "has_name" => true),
            array("first_delimiter" => "{% eval",       "second_delimiter" => " %}",        "name" => "eval",       "end" => "{% endeval %}",   "has_name" => false),
            array("first_delimiter" => "{{ path(\"",    "second_delimiter" => " }}",        "name" => "path",       "end" => ") }}",            "has_name" => true),
        );

        $file = file_get_contents($file);

        foreach ($this->knownBlocksTypes as $type) {
            $fullBlocks = explode("$type[first_delimiter]", $file);
            array_splice($fullBlocks, 0, 1);
            foreach ($fullBlocks as $current) {
                if (count(explode("$type[second_delimiter]", $current)) > 1) {
                    $current = explode($type["end"], $current)[0];
                    if ($type["has_name"]) {
                        $name = explode("$type[second_delimiter]", $current)[0];
                    } else {
                        $name = "";
                    }
                    if (count(explode("$name$type[second_delimiter]", $current)) > 1) {
                        $content = explode("$name$type[second_delimiter]", $current)[1];
                    } else {
                        $content = "";
                    }
                    $block = array(
                        "type"      => $type["name"],
                        "name"      => $name,
                        "content"   => $content,
                        "printed"   => false,
                    );
                    array_push($blocks, $block);
                }
            }
        }

        return $blocks;
    }

    function generate()
    {

        global $env;

        $layout = file_get_contents($this->layoutFile);

        $this->blocks = $this->getBlocks($this->file);

        if (!file_exists($this->filePath)) {
            fopen($this->filePath, "w");
        }

        foreach ($this->blocks as $block) {
            switch ($block["type"]) {
                case "block":
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
                    break;
                case "include":
                    $fullBlock = "{% include $block[name] %}";

                    $blockName = str_replace('"', "", $block["name"]);

                    $content = file_get_contents("$env[TEMPLATES_FOLDER]/$blockName");

                    $layout = str_replace($fullBlock, $content, $layout);
                    break;
                case "if":
                    $condition = "{% if $block[name] %}";
                    $condition = str_replace("{% if ", "if(", $condition);
                    $condition = str_replace(" %}", ") return true; return false;", $condition);
                    if (eval($condition)) {
                        $layout = str_replace("{% if $block[name] %}$block[content]{% endif %}", $block["content"], $layout);
                    } else {
                        $layout = str_replace("{% if $block[name] %}$block[content]{% endif %}", "", $layout);
                    }
                    break;
                case "path":
                    // getRoute($block["name"]);
                    break;
                case "eval":
                    $content = eval($block["content"]);
                    $block["content"] = str_replace("%}", "", $block["name"]);
                    $fullBlock = "{% eval %}$block[content]{% endeval %}";
                    $layout = str_replace($fullBlock, $content, $layout);
                    break;
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

    function getLayout()
    {
        global $env;

        $this->layoutFile = explode("{% extends ", $this->fileContent)[1];
        $this->layoutFile = explode(" %}", $this->layoutFile)[0];
        $this->layoutFile = str_replace(["'", '"'], "", $this->layoutFile);
        $this->layoutFile = "$env[TEMPLATES_FOLDER]/$this->layoutFile";

        $this->layoutBlocks = $this->getBlocks($this->layoutFile);

        return $this->layoutBlocks;
    }

    public function build()
    {
        $this->layout = $this->getLayout();
        $this->generate();
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }
}
