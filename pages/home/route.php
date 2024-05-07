<?php
 require_once(__DIR__ . "/../../php/_protect.php");

 $subMenu = isset($_GET['subMenu']) ? $_GET['subMenu'] : 'unknow';
 switch ($subMenu) {
     default:
         require_once(__DIR__ . "/default_content.php");
 }