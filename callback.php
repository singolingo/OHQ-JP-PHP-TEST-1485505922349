<?php
$state = $code = $appid = $clientid = $clientsecret =$result = "初期値";
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

// ログイン用のWebAPIをここに記載
$url = 'https://data-stg-jp.omronconnect.mobi/api/apps/bdf72f34/oauth2/token';
$http_post_body = array(
		"grant_type" => "authorization_code",
		"code" => $code,
		"redirect_uri" => "https://ohq-jp-php-test.mybluemix.net/callback.php",
		"client_id" => "a9f0vfobhlh6n9c4q2q6d8v03al719kj5sh8",
		"client_secret" => "utu14mjvqtbanuo63nn2v2dqk9e5h9qajuq85tm2ogbtl40r3aga5pbq7m772u07"
);

function http_post ($url, $http_post_body)
{
	$data_url = http_build_query ($http_post_body);
	$data_len = strlen ($data_url);

	return array (
			'content'=>  file_get_contents (
					$url,
					false,
					stream_context_create (
							array ('http' =>
									array (
											'method'=>'POST',
											//	'header'=>"Content-Type: application/x-www-form-urlencoded\r\nContent-Length: $data_len\r\n",
											'header'=>"Content-Type: application/x-www-form-urlencoded",
											'content'=>$data_url)
							)
							)
					),
			'headers'=> $http_response_header
	);
}



?>

<!DOCTYPE html>
<html lang = “ja”>
<head>
<meta charset = “UFT-8”>
<title>OGSC クラウドから、コールバックで返却される値を取得。</title>

<script type="text/javascript">

 function onLoginButton1_Click(){
	 header("Content-Type: text/javascript; charset=utf-8");
	 $respons_json = http_post($url, $http_post_body);
	 $result = json_decode($respons_json);

 }

</script>

</head>
<body>
<h1>外部サービスにＯＧＳＣがコールバックしました。</h1>


<h1>ＯＧＳＣからの返却値</h1>
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
<BR>
<BR>
<h1>ＳＴＥＰ２：ＯＧＳＣに外部サービスがデータ取得のためAPIログイン操作をします。</h1>
  <input type="button" id="btn1" value="ＯＧＳＣクラウドにシステムがログイン" onclick="onLoginButton1_Click()" /><br>

<h1>APIログイン後の受診データ</h1>
<?php var_dump($result);?>
<?php echo $result ; ?>


</body>
</html>