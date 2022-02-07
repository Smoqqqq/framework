<?php

namespace Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Route
{
    /**
     * @Required
     * 
     * @var string
     */
    public $route;

    /**
     * @Required
     * 
     * @var string
     */
    public $name;
    
    public function __construct(array $values)
    {
        $this->route = $values["route"];
        $this->name = $values["name"];
    }
}

