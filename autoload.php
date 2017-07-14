<?php
$root = "src";

function __autoload($className){
      if(file_exists($className))
      {
            require_once $className;
            return true;
      }
      return false; 
}

function registerPHPClasses($rootpath = '.'){
      $output = [];
      $fileinfos = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootpath)
      );
      foreach($fileinfos as $pathname => $fileinfo) {
            $output[] = $pathname;
            if (!$fileinfo->isFile()) continue;
            else{
                  if(substr($pathname, -4) == ".php"){
                        __autoload($pathname);
                  }
            }
      }
      return $output;
}

registerPHPClasses($root);

function canClassBeAutloaded($className) { 
      return class_exists($className); 
} 
?>