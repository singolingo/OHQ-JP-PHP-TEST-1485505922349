<?php
// Content-TypeをJSONに指定する
header('Content-Type: application/json');


$result = "初期値";

//OMRON connect アクセス鍵の情報（ログイン結果をここに入力）
$appid = "bdf72f34";
$clientid = "a9f0vfobhlh6n9c4q2q6d8v03al719kj5sh8";
$clientsecret = "utu14mjvqtbanuo63nn2v2dqk9e5h9qajuq85tm2ogbtl40r3aga5pbq7m772u07";
$callbackurl = "https://ohq-jp-php-test.mybluemix.net/callback.php";
$id = "191e695246a0-2198-6e11-cb6e-01909381";
//$access_token = "iiBWbzIASOhbQA3WWu6PVyeGnx4p6kCbTYtNMM3UFIc";
$access_token = "pX6PBb1PJv00VPs9W6ewzgcZIGsB_9WmyKQnmUl4nAo";
$refresh_token = "br-jqU-hxakd_6bBck0ufBq3Uz5iXINJOoUDcJHWm5s";
$id_token = "eyJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJhOWYwdmZvYmhsaDZuOWM0cTJxNmQ4djAzYWw3MTlrajVzaDgiLCJzdWIiOiIxOTFlNjk1MjQ2YTAtMjE5OC02ZTExLWNiNmUtMDE5MDkzODEiLCJhcHBJRCI6ImJkZjcyZjM0IiwiaXNzIjoiaHR0cHM6XC9cL2RhdGEtc3RnLWpwLm9tcm9uY29ubmVjdC5tb2JpXC9hcGlcL2FwcHNcL2JkZjcyZjM0IiwiZXhwIjoxNDg1ODUyNTEwLCJpYXQiOjE0ODU4NDg5OTV9.GL_nszrhtUysLjYutL5y7tkk_6mbIKg_ouY9IU76b3G6AK4i8SC_rfTYzPB8dFPKWuEbQ8pJs18eREK3JW7gMXlUrJNegoMJA06OhZK31nXIDiACaNuPXJhLMiLVMSeyAYonyRPmbvJSterQ52HhCKgDkcR91C8nvjneag1e0eQ";
$expires_in = "3514";
$token_type = "bearer";

//ＷｅｂＡＰＩリクエスト
$method = "POST";
$header = "Content-Type: application/json, Authorization: Bearer ".$access_token ;

$paginationKey = "";  //初回データ取得時は不要
$deviceCategory = "0"; // ★必須 （血圧計：0、体組成計：1、歩数計：2、睡眠計：3、体温計：4、血糖計：5）
$deviceModel = "";
$deviceSerialID = "";
$userNumberInDevice = "";
$searchDateFrom = "1483196400000"; //ceil(microtime(true)*1000); //▲準必須
$searchDateTo = "1485874800000"; //ceil(microtime(true)*1000); //▲準必須
$searchDeviceDateFrom = "20170101000000"; //▲準必須
$searchDeviceDateTo = "20170201000000"; //▲準必須



// ログイン用のWebAPIをここに記載
$url = 'https://data-stg-jp.omronconnect.mobi/api/apps/'.$appid.'/server-code/versions/current/measureData';
$http_post_body = array(
		"paginationKey" => "", //初回データ取得時は不要
		"deviceCategory" => $deviceCategory,
		"deviceModel" => $deviceModel,
		"deviceSerialID" => $deviceSerialID,
		"userNumberInDevice" => $userNumberInDevice,
		"searchDateFrom" => $searchDateFrom,
		"searchDateTo" => $searchDateTo,
		"searchDeviceDateFrom" => $searchDeviceDateFrom,
		"searchDeviceDateTo" => $searchDeviceDateTo
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
											'method'=> $method,
											'header'=> $header,
											'content'=>$data_url)
							)
							)
					),
			'headers'=> $http_response_header
	);
};

//データを取得
$respons_json = http_post ($url, $http_post_body);
$result = json_decode ($respons_json);


?>

<!DOCTYPE html>
<html lang = “ja”>
<head>
<meta charset = “UFT-8”>
<title>OGSC クラウドから、コールバックで返却される値を取得。</title>

<script type="text/javascript">
 var target1 = document.getElementById("message1");

 function onLoginButton1_Click(){
	 target1.innerHTML = "<?php echo $result; ?>";

 };

</script>

</head>
<body>
<h1>ＯＧＳＣからの返却値</h1>
DUMP:<?php var_dump($respons_json);?>
RESULT:<?php echo ($result);?>
METHOD:<?php echo $method;?>

HEADER:<?php echo $header; ?>

PAGINTIONKEY:<?php echo $paginationKey; ?>

DEVICECATEGORY:<?php echo $deviceCategory; ?>

DEVICEMODEL:<?php echo $deviceModel; ?>

DEVICESERIALID:<?php echo $deviceSerialID; ?>

USERNUMBERINDEVICE:<?php echo $userNumberInDevice; ?>

SEARCHDATE:<?php echo $searchDateFrom; ?>　-　<?php echo $searchDateTo; ?>

SEARCHDATE2:<?php echo $searchDeviceDateFrom; ?>　-　<?php echo $searchDeviceDateTo;?>



</body>
</html>