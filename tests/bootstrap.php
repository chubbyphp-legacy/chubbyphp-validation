<?php

declare(strict_types=1);

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->setPsr4('Chubbyphp\Tests\ValidationDoctrine\\', __DIR__. '/Doctrine');
$loader->setPsr4('Chubbyphp\Tests\ValidationModel\\', __DIR__. '/Model');
$loader->setPsr4('Chubbyphp\Tests\Validation\\', __DIR__);
