<?php
    /*define("addr","http://localhost/");
    if($_SERVER['HTTP_REFERER'] != constant("addr"))
        die("access is denied");*/
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>English Chatter</title>
            <link rel="stylesheet" type="text/css" href="main.css">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script>
                function res(){
                    cont.style.height = (window.innerHeight / 3 / 2) * 5 - 30 + "px";
                    prev.style.textIndent = msg.offsetWidth + "px";
                    names.style.height = cont.clientHeight - panel.clientHeight - 5 + "px";
                    text.style.width = cont.clientWidth - names.clientWidth - 13 + "px";
                    text.style.height = names.clientHeight + "px";
                }
                function cop(){
                    if(msg.value.charAt(msg.value.length - 1) == " " && msg.value.charAt(msg.value.length - 2) == " ")
                        msg.value = prev.innerHTML;
                    else{
                        prev.innerHTML = msg.value;
                        panel.scrollTop = panel.scrollHeight;
                    }
                }
                function key(e){
                    if(e.keyCode != 13)
                        return;
                    msg.value = "";
                    var dt = new Date();
                    var para = document.createElement('p');
                    para.innerHTML = "<b>" + nick.innerHTML + ":</b><i>" + dt.getHours() + ":" + dt.getMinutes() + "</i> : " + prev.innerHTML;
                    text.insertBefore(para,text.firstChild);
                    var xhttp = new XMLHttpRequest();
                    var params = "sender=" + nick.innerHTML + "&message=" + prev.innerHTML;
                    xhttp.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            prev.innerHTML = "ok!";
                        }
                    }
                    xhttp.open("POST", "write.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send(params);
                }
                function recv(){
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            procXML(this);
                        }
                    }
                    xhttp.open("GET", "procdata.php?nick=" + nick.innerHTML, true);
                    xhttp.send();
                }
                function procXML(resp){
                    var xml = resp.responseXML;
                    var nms = xml.getElementsByTagName("name");
                    var msgs = xml.getElementsByTagName("message");
                    var tm = Number(time.innerHTML);
                    var nmb = tm;
                    names.innerHTML = "";
                    for(var i = 0; i < nms.length; i++)
                    {
                        if(nms[i].childNodes[0].nodeValue == nick.innerHTML)
                            names.innerHTML += "<span><b>" + nms[i].childNodes[0].nodeValue + "</b></span><hr>";
                        else
                            names.innerHTML += "<span>" + nms[i].childNodes[0].nodeValue + "</span><hr>";
                    }
                    for(var i = 0, tmp = 0, dt; i< msgs.length; i++)
                    {
                        tmp = Number(msgs[i].getAttribute('time'))
                        if(tmp > tm)
                        {
                            if(tmp > nmb)
                                nmb = tmp;
                            if(msgs[i].getAttribute('sender') == nick.innerHTML)
                                continue;
                            dt = new Date(tmp * 1000);
                            var para = document.createElement('p');
                            para.innerHTML = "<b>" + msgs[i].getAttribute('sender') + ":</b><i>" + dt.getHours() + ":" + dt.getMinutes() + "</i> : " + msgs[i].childNodes[0].nodeValue;
                            text.insertBefore(para, text.firstChild);
                        }
                            
                    }
                    time.innerHTML = nmb;
                }
            </script>
        </head>
        <body>
            <h3 style="text-align:center">English Chatter</h3>
            <div id="container">
                <div id="panel">
                    <input id="msg" type="text">
                    <p id="preview"></p>
                </div>
                <div id="names">
                </div>
                <div id="text">
                </div>
            </div>
            <div id="nick"><?php echo $_GET['nick'];?></div>
            <div id="time"><?php echo time();?></div>
        </body>
        <script>
            var cont = document.getElementById("container");
            var prev = document.getElementById("preview");
            var msg = document.getElementById("msg");
            var panel = document.getElementById("panel");
            var names = document.getElementById("names");
            var text = document.getElementById("text");
            var nick = document.getElementById("nick");
            var time = document.getElementById("time");
            window.addEventListener("resize",res);
            res();
            msg.addEventListener("input",cop);
            msg.addEventListener("keypress",key);
            recv();
            setInterval(recv, 2300);
        </script>
    </html>