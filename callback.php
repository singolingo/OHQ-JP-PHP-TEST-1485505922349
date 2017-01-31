<?php

$state = $code = $appid = $clientid = $clientsecret = "初期値";


IF(isset($_GET["code"])){
	$code = $_GET["code"];
}

IF(isset($_GET["state"])){
	$state = $_GET["state"];
}

IF(isset($_POST["AppID"])){
	$appid = $_POST["AppID"];
}

IF(isset($_POST["ClientID"])){
	$clientid = $_POST["ClientID"];
}

IF(isset($_POST["ClientSecret"])){
	$clientsecret = $_POST["ClientSecret"];
}



?>

<!DOCTYPE html>
<html lang = “ja”>
<head>
<meta charset = “UFT-8”>
<title>OGSC クラウドから、コールバックで返却される値を取得。</title>

</head>
<body>
<h1>コールバック先</h1>


<BR>
code:
<?php
echo $code;
?>
<BR>
state:
<?php
echo $state;
?>
<BR>
appid:
<?php
echo $appid;
?>
<BR>
clientid:
<?php
echo $clientid;
?>
<BR>
clientsecret:
<?php
echo $clientsecret;
?>
<BR>



</body>
</html>