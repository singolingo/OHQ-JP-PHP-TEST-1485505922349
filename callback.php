<?php

$code = $appid = $clientid = $clientsecret = $state = "初期値";

if (isset($_GET["code"])) {
	$code = $_GET[‘code’];
}
if (isset($_POST["AppID"])) {
	$appid = $_POST[‘AppID’];
}
if (isset($_POST["ClientID"])) {
	$clientid = $_POST[‘ClientID’];
}
if (isset($_POST["ClientSecret"])) {
	$clientsecret = $_POST[‘ClientSecret’];
}
if (isset($_POST["State"])) {
	$state = $_POST[‘State’];
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
<?php
echo $code;
?>
<BR>
<?php
echo $appid;
?>
<BR>
<?php
echo $clientid;
?>
<BR>
<?php
echo $clientsecret;
?>
<BR>
<?php
echo $state;
?>
<BR>



</body>
</html>