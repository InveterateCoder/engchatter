<?php
    define("addr","http://localhost/");
    /*if($_SERVER['HTTP_REFERER'] != constant("addr") || $_SERVER['REQUEST_METHOD'] != 'POST')
        die("access is denied");*/
    if(strlen($_POST['nick']) == 0 || strlen($_POST['nick']) > 4)
        die("requirements not met");
    $time = time();
    $file = fopen("time.txt","r+");
    if(!$file)
        die();
    $count=1;
    while(!flock($file, LOCK_EX))
    {
        if($count>3)
        {
            fclose($file);
            die();
        }
        $count++;
    }
    $n = fread($file, 13);
    rewind($file);
    $xml = simplexml_load_file("database.xml") or die("failed to load xml");
    if($n + 10 < $time)
    {
        for($i = $xml -> messages -> message -> count() - 1; $i >= 0; $i--)
            unset($xml -> messages -> message[$i]);
        for($i = $xml -> names -> name -> count() - 1; $i >= 0; $i--)
            unset($xml -> names -> name[$i]);
    }
    else
    {
        foreach($xml -> names -> name as $name)
        {
            if($name == $_POST['nick'])
            {
                $xml -> asXML("database.xml");
                die("specified nick is already in use");
            }
        }
    }
    $xml -> names -> addChild("name", $_POST['nick']) -> addAttribute("time", $time);
    $xml -> asXML("database.xml");
    fwrite($file, $time);
    flock($file,LOCK_UN);
    fclose($file);
    header("Location: body.php?nick=".$_POST['nick']);
?>