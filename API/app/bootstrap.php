<?php
  require_once('config/Config.php');

  //AutoLoad
  spl_autoload_register(function($className){
      require_once('libraries/' .$className. '.php');
  });
