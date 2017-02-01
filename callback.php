
<?php
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

$result = http_post ($url, $http_post_body);
$response_json= json_decode ($result["content"],true);


?>

<!DOCTYPE html>
<html lang = “ja”>
<head>
<meta charset = “UFT-8”>
<title>OGSC クラウドから、コールバックで返却される値</title>
<script type="text/javascript">
 var target1 = document.getElementById("message1");

 function onLoginButton2_Click(){
	<?php
	$url_2 = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
	$url_2 .= $_SERVER["HTTP_HOST"];
	$url_2 .= "/Chart.php?access_token=";
	$url_2 .= $response_json['access_token']
	?>
	 window.location.href = "<?php print ($url_2);?>";
	 };
</script>

</head>
<body>
<h1>ＯＧＳＣから外部サービスに、認証情報がコールバックされました。</h1>
■ＯＧＳＣからの返却値
<BR>
<font color=blue>code:</font><?php echo $code; ?>
<BR>
<font color=blue>state:</font><?php echo $state; ?>
<BR>
<font color=blue>appid:</font><?php echo $appid; ?>
<BR>
<font color=blue>clientid:</font><?php echo $clientid; ?>
<BR>
<font color=blue>clientsecret:</font><?php echo $clientsecret; ?>
<BR>
<font color=blue>url:</font><?php echo $url; ?>
<BR><BR>
■OGSCから送信されたデータ<BR>
<?php var_dump($result);?>
<BR>
<font color=blue>id:</font><?php echo  $response_json["id"];?>
<BR>
<font color=blue>access_token:</font><?php echo  $response_json["access_token"];?>
<BR>
<font color=blue>refresh_token:</font><?php echo  $response_json["refresh_token"];?>
<BR>
<font color=blue>id_token:</font><?php echo  $response_json["id_token"];?>
<BR>
<font color=blue>expires_in:</font><?php echo  $response_json["expires_in"];?>
<BR>
<font color=blue>token_type:</font><?php echo  $response_json["token_type"];?>
<BR>

<BR>
<h1>ＳＴＥＰ２：アクセストークンを使って、外部サービスがＯＧＳＣに接続します。</h1>
  <input type="button" id="btn1" value="ＯＧＳＣとシステム連携" onclick="onLoginButton2_Click();" /><br>


</body>
</html>