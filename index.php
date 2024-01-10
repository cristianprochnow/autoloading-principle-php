<?php

spl_autoload_register(function($class) {
  $path = str_replace(
    'bio', 'src',
    lcfirst(str_replace('\\', '/', $class) . '.php')
  );

  require_once __DIR__ . '/' . $path;
});

use Bio\Animals\Cat;

$cat = new Cat();
