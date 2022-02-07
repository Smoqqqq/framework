<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once("_bin/env.php");
require_once("_bin/Doctrine/GetEntities.php");
require_once("_bin/functions.php");

// replace with mechanism to retrieve EntityManager in your app
$entityManager = GetEntityManager();

return ConsoleRunner::createHelperSet($entityManager);