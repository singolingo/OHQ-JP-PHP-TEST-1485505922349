<?php
$state = $code = $result = "初期値";
IF(isset($_GET["code"])){
	$code = $_GET["code"];
}
IF(isset($_GET["state"])){
	$state = $_GET["state"];
}


//OMRON connect アクセス情報
$appid = "bdf72f34";
$clientid = "a9f0vfobhlh6n9c4q2q6d8v03al719kj5sh8";
$clientsecret = "utu14mjvqtbanuo63nn2v2dqk9e5h9qajuq85tm2ogbtl40r3aga5pbq7m772u07";
$callbackurl = "https://ohq-jp-php-test.mybluemix.net/callback.php";

// ログイン用のWebAPIをここに記載
$url = 'https://data-stg-jp.omronconnect.mobi/api/apps/'.$appid.'/oauth2/token';
$http_post_body = array(
		"grant_type" => "authorization_code",
		"code" => $code,
		"redirect_uri" => $callbackurl,
		"client_id" => $clientid,
		"client_secret" => $clientsecret
);

$url_2 = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
$url_2 .= $_SERVER["HTTP_HOST"]."Chart.php";


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
};

$respons_json = http_post ($url, $http_post_body);

$result = json_decode ($respons_json);


?>

<!DOCTYPE html>
<html lang = “ja”>
<head>
<meta charset = “UFT-8”>
<title>OGSC クラウドから、コールバックで返却される値</title>
<script type="text/javascript">
 var target1 = document.getElementById("message1");

 function onLoginButton2_Click(){
 	window.location.href = "<?php print ($url_2);?>>";
	 };
</script>

</head>
<body>
<h1>ＯＧＳＣから外部サービスに、認証情報がコールバックされました。</h1>
<h1>ＯＧＳＣからの返却値</h1>
<BR>
code:<?php echo $code; ?>
state:<?php echo $state; ?>
appid:<?php echo $appid; ?>
clientid:<?php echo $clientid; ?>
clientsecret:<?php echo $clientsecret; ?>
url:<?php echo $url; ?>

<BR>OGSCから送信されたデータ
<?php var_dump($respons_json); ?>

<BR>
<h1>ＳＴＥＰ２：アクセストークンを使って、外部サービスがＯＧＳＣに接続します。</h1>
  <input type="button" id="btn1" value="ＯＧＳＣとシステム連携" onclick="onLoginButton2_Click();" /><br>

<h1>APIログイン後の受診データ</h1>
    <div id="message1"></div><br>


</body>
</html>