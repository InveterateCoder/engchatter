<?php
     define("addr","http://localhost/");
    /*if($_SERVER['HTTP_REFERER'] != constant("addr"))
        die("access is denied");*/
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
    if($n + 7 < $time)
    {
        $count=1;
        while(!$xml = simplexml_load_file("database.xml"))
        {
            if($count>3)
            {
                flock($file,LOCK_UN);
                fclose($file);
                die();
            }
            $count++;
        }
        for($i = $xml -> messages -> message -> count() - 1; $i >= 0; $i--)
        {
            if($xml -> messages -> message[$i]["time"] + 7 < $time)
                unset($xml -> messages -> message[$i]);
        }
        for($i = $xml -> names -> name -> count() - 1; $i >= 0; $i--)
        {
            if($xml -> names -> name[$i]["time"] + 7 < $time)
                unset($xml -> names -> name[$i]);
            elseif($xml -> names -> name[$i] == $_GET['nick'])
            {
                $xml -> names -> name[$i]["time"] = $time;
            }
                
        }
        $xml ->asXML("database.xml");
        fwrite($file, $time);
    }
    else
    {
        $count=1;
        while(!$xml = simplexml_load_file("database.xml"))
        {
            if($count>3)
            {
                flock($file,LOCK_UN);
                fclose($file);
                die();
            }
            $count++;
        }
        for($i = $xml -> names -> name -> count() - 1; $i >= 0; $i--)
        {
            if($xml -> names -> name[$i] == $_GET['nick'])
            {
                 $xml -> names -> name[$i]["time"] = $time;
                 break;
            }   
        }
        $xml ->asXML("database.xml");
    }
    flock($file, LOCK_UN);
    fclose($file);
    header("Location: database.xml");
?>