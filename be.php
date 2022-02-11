<html>
	<head>
        <meta http-equiv="refresh" content="10">
        <style>
            .h{
                color: #00838f;
            }
            .d{
                color: #424242;
            }
            .p{
                color: #4e4e4e;
            }
            
           @media(prefers-color-scheme: dark){
            .h{
                color: #80cbc4;
            }
            .d{
                color: #e0e0e0;
            }
            .p{
                color: #bdbdbd;
            }
           }
        </style>
	</head>
    <body style="font-family:'monospace'">
<?php
/**
 * @Author Ruokwok
 * @Date 2019-10-13 18:46
 *
 * @version 1.1.0
 */

error_reporting(E_ALL ^ E_WARNING);
error_reporting(E_ALL ^ E_NOTICE);

$port = 19132;
if($_GET['ip'] == null ) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
    exit("<h1>IP地址为空</h1>");
}
if ($_GET['port'] != null) {
    $port = $_GET['port'];
}
$result = udpGet($_GET['ip'],$port);


//echo $result."<br>";
$result1 = strstr($result, "MCPE");
//echo $result1;
$str = explode(";", $result1);
if (!strpos($str[0], "CPE")) {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
    exit("<h1>操作超时</h1>");
}

if ($_GET['type'] == "json") {
    $motd = [
        "{$str[1]}",
        "{$str[2]}",
        "{$str[3]}",
        "{$str[4]}",
        "{$str[5]}",
        "{$str[6]}",
        "{$str[7]}",
        "{$str[8]}",
    ];
    echo json_encode($motd);
    exit();
}

echo "<!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes\" />
<title>服务器状态查询</title>
</head>
<body>";
echo "<h3 class='h' style='line-height:16px;transform:translateY(19px)'>&#8195在线人数：{$str[4]}/{$str[5]}</h3>"."<div class='d' style='height:8px;width:0px;line-height:36px;transform:translate(14px,1px)'>━━━━</div>";
$core = "未知";
if (count($str) == 10 && strpos($str[6], "-")) {
    $core = "NukkitX";
} elseif (count($str) == 10 && strpos($str[6],"-") === 0) {
    $core = "Nemisys";
} elseif (count($str) == 10 && !strpos($str['6'], "-")) {
    $core = "PocketMine-MP";
} elseif (count($str) == 13) {
    $core = "Bedrock Server";
}

if ($str[10] == null) {
    $str[10] = 19132;
}

if ($_GET['ip'] == "127.0.0.1") {
    $addr= "MC.BugJump972.xyz";
}else{
    $addr= $_GET['ip'];
}

echo "<p class='p'>&#8195<strong>MOTD：</strong>{$str[1]}<br>&#8195<strong>默认游戏模式：</strong>{$str[8]}</p>";
echo "<p class='p'>&#8195<strong>地址：</strong>$addr<br>&#8195<strong>IPv4/IPv6端口：</strong>{$str[10]}/{$str[11]}<br>&#8195<strong>客户端版本：</strong>{$str[3]}</p>";
echo "<p class='p'>&#8195<strong>服务端软件：</strong>$core<br>&#8195<strong>协议版本：</strong>{$str[2]}</p>";

function udpGet($ip, $port) {

    try{
        $handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);
    } catch (Exception $e) {
        echo "Exception";
    }

    if( !$handle ){

        die("ERROR: {$errno} - {$errstr}\n");

    }

    fwrite($handle, hex2bin('0100000000240D12D300FFFF00FEFEFEFEFDFDFDFD12345678')."\n");

    $result = fread($handle, 1024);

    fclose($handle);

    return $result;

}

?>

    </body>
</html>