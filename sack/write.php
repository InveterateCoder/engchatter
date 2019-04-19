<?php
     define("addr","http://localhost/");
    /*if($_SERVER['HTTP_REFERER'] != constant("addr") || $_SERVER['REQUEST_METHOD'] != 'POST')
        die("access is denied");*/
    $time = time();
    $xml = simplexml_load_file("database.xml") or die();
    $item = $xml -> messages -> addChild("message",$_POST['message']);
    $item -> addAttribute("time", $time);
    $item -> addAttribute("sender", $_POST['sender']);
    $xml -> asXML("database.xml");
?>