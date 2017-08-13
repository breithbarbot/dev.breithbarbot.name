<?php
/******************************************************************************
 * Copyright (c) 2017 dev.breithbarbot.name. All rights reserved.             *
 * Author      : Breith Barbot                                                *
 * Updated at  : 13/08/17 08:15                                               *
 * File name   : index.php                                                    *
 * Description :                                                              *
 ******************************************************************************/

function get_ip_address()
{
    if (null !== $_SERVER) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ip2long($_SERVER['HTTP_X_FORWARDED_FOR']) !== false) {
            $ipadres = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && ip2long($_SERVER['HTTP_CLIENT_IP']) !== false) {
            $ipadres = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ipadres = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR') && ip2long(getenv('HTTP_X_FORWARDED_FOR')) !== false) {
            $ipadres = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP') && ip2long(getenv('HTTP_CLIENT_IP')) !== false) {
            $ipadres = getenv('HTTP_CLIENT_IP');
        } else {
            $ipadres = getenv('REMOTE_ADDR');
        }
    }

    return $ipadres;
}

?>
<!doctype html>
<!-- ========================================================================================
 *
 * ______          _ _   _     _                _           _
 * | ___ \        (_) | | |   | |              | |         | |
 * | |_/ /_ __ ___ _| |_| |__ | |__   __ _ _ __| |__   ___ | |_   _ __   __ _ _ __ ___   ___
 * | ___ \ '__/ _ \ | __| '_ \| '_ \ / _` | '__| '_ \ / _ \| __| | '_ \ / _` | '_ ` _ \ / _ \
 * | |_/ / | |  __/ | |_| | | | |_) | (_| | |  | |_) | (_) | |_ _| | | | (_| | | | | | |  __/
 * \____/|_|  \___|_|\__|_| |_|_.__/ \__,_|_|  |_.__/ \___/ \__(_)_| |_|\__,_|_| |_| |_|\___|
 *
 *                        /!\  Developed with ❤ by Breith Barbot  /!\
 *
 * ====================================================================================== -->
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="robots" content="noindex, nofollow">
        <title>Développement - Breith Barbot</title>
        <style>
            body{background:#242425}.center-Container{position:relative}.absolute-center{height:100%;min-width:400px;overflow:auto;margin:auto;position:fixed;z-index:1;top:0;left:0;bottom:-60px;right:0}#ip-client{background:#424242;color:#242425;position:absolute;bottom:0;right:0;border-top-left-radius:4px;padding:5px;font-weight:500;font-family:monospace;z-index:10}
        </style>
    </head>
    <body>
        <div class="center-Container">
            <div class="absolute-center">
                <canvas id="canvas"></canvas>
            </div>
        </div>

        <div id="ip-client" title="Your Public IP"><?php echo get_ip_address(); ?></div>

        <script>
            function tick(){ctx.clearRect(0,0,cw,ch),ctx.drawImage(bufferCanvas,0,0,current,txtH,sx,sy,current,txtH),ctx.save(),ctx.globalAlpha=.05,ctx.globalCompositeOperation="lighter",drawRays(current)?(current++,current=Math.min(current,txtW),window.requestAnimationFrame(tick)):fadeOut(),ctx.restore()}function fadeOut(){ctx.clearRect(0,0,cw,ch),ctx.globalAlpha*=.95,ctx.drawImage(bufferCanvas,0,0,current,txtH,sx,sy,current,txtH),ctx.globalAlpha>.01?window.requestAnimationFrame(fadeOut):window.setTimeout(restart,500)}function restart(){for(var a=0;a<rays.length;a++)rays[a].reset();ctx.globalAlpha=1,buffer.clearRect(0,0,txtW,txtH),current=0,tick()}function drawRays(a){var b=0;ctx.beginPath();for(var c=0;c<rays.length;c++){var d=rays[c];d.col<a&&(b+=d.draw())}return ctx.stroke(),b!==rays.length}function Ray(a,b,c){this.col=b,this.row=a;var d=sx+b,e=sy+a,f=c,g=txtH/1.5,h=pi2*(this.row-.5*g)/g;0===h&&(h=(Math.random()-.5)*pi2);var i=.02*Math.sign(h),j=2*pi*(this.col-.5*txtW)/txtW;0===j&&(j=(Math.random()-.5)*pi);var k=.02*Math.sign(j);i+=.005*(Math.random()-.5);var l=0,m=2*Math.random()+2,n=!1;this.reset=function(){j=2*pi*(this.col-.5*txtW)/txtW,h=pi2*(this.row-.5*g)/g,0===h&&(h=.5*-pi2),l=0,n=!1},this.draw=function(){return 0>l?(n||(buffer.fillStyle=f,buffer.fillRect(this.col,this.row,1,1),n=!0),1):(ctx.moveTo(d,e),ctx.quadraticCurveTo(d+Math.cos(j)*l*.5,e+Math.sin(j)*l*.5,d+Math.cos(h)*l,e+Math.sin(h)*l),h+=i,j+=k,l+=Math.cos(h)*m,0)}}function notifyMe(a){if("granted"===Notification.permission){new Notification("Hi "+a,{icon:"assets/img/user.png"})}else"denied"!==Notification.permission&&Notification.requestPermission(function(b){if("granted"===b){new Notification("Hi "+a,{icon:"assets/img/user.png"})}})}var txt="<Breith Barbot/>",txtH=90,font="sans-serif",bg="#242425",rayColor1="#093f7f",rayColor2="#f12e59",rayColor3="#093f7f",canvas=document.getElementById("canvas"),ctx=canvas.getContext("2d"),cw=canvas.width=window.innerWidth,ch=canvas.height=.9*window.innerHeight,w2=cw/2,h2=ch/2,pi=Math.PI,pi2=.5*pi,txtCanvas=document.createElement("canvas"),txtCtx=txtCanvas.getContext("2d");txtCtx.font=txtH+"px "+font,txtCtx.textBaseline="middle";var txtW=Math.floor(txtCtx.measureText(txt).width);txtCanvas.width=txtW,txtCanvas.height=1.2*txtH;var gradient=ctx.createRadialGradient(w2,h2,0,w2,h2,txtW);gradient.addColorStop(0,rayColor3),gradient.addColorStop(.5,rayColor2),gradient.addColorStop(1,rayColor1),ctx.strokeStyle=gradient,txtCtx.fillStyle=gradient,txtCtx.font=txtH+"px "+font,txtCtx.textBaseline="middle",txtCtx.fillText(txt,0,.5*txtH),txtH*=1.5;var bufferCanvas=document.createElement("canvas");bufferCanvas.width=txtW,bufferCanvas.height=txtH;for(var buffer=bufferCanvas.getContext("2d"),sx=.5*(cw-txtW),sy=.5*(ch-txtH),rays=[],txtData=txtCtx.getImageData(0,0,txtW,txtH),i=0;i<txtData.data.length;i+=4){var ii=i/4,row=Math.floor(ii/txtW),col=ii%txtW,alpha=txtData.data[i+3];if(0!==alpha){var c="rgba(";c+=[txtData.data[i],txtData.data[i+1],txtData.data[i+2],alpha/255],c+=")",rays.push(new Ray(Math.floor(ii/txtW),ii%txtW,c))}}var current=0;Notification.requestPermission(),tick(),notifyMe('<?php echo get_ip_address(); ?>');
        </script>
    </body>
</html>
