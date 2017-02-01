<?php
// Content-TypeをJSONに指定する
$result = "初期値";

//OMRON connect アクセス鍵の情報（ログイン結果をここに入力）
$appid = "bdf72f34";
$clientid = "a9f0vfobhlh6n9c4q2q6d8v03al719kj5sh8";
$clientsecret = "utu14mjvqtbanuo63nn2v2dqk9e5h9qajuq85tm2ogbtl40r3aga5pbq7m772u07";
$callbackurl = "https://ohq-jp-php-test.mybluemix.net/callback.php";
$id = "191e695246a0-2198-6e11-cb6e-01909381";
$access_token = "Q-xx6FUFI91TymJXqPez4aQ_O0Th_O8Pc-hA9Thzcjg";  // dummy
IF(isset($_GET["access_token"])){
	$access_token = $_GET["access_token"];
}
$refresh_token = "br-jqU-hxakd_6bBck0ufBq3Uz5iXINJOoUDcJHWm5s";
$id_token = "eyJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJhOWYwdmZvYmhsaDZuOWM0cTJxNmQ4djAzYWw3MTlrajVzaDgiLCJzdWIiOiIxOTFlNjk1MjQ2YTAtMjE5OC02ZTExLWNiNmUtMDE5MDkzODEiLCJhcHBJRCI6ImJkZjcyZjM0IiwiaXNzIjoiaHR0cHM6XC9cL2RhdGEtc3RnLWpwLm9tcm9uY29ubmVjdC5tb2JpXC9hcGlcL2FwcHNcL2JkZjcyZjM0IiwiZXhwIjoxNDg1ODUyNTEwLCJpYXQiOjE0ODU4NDg5OTV9.GL_nszrhtUysLjYutL5y7tkk_6mbIKg_ouY9IU76b3G6AK4i8SC_rfTYzPB8dFPKWuEbQ8pJs18eREK3JW7gMXlUrJNegoMJA06OhZK31nXIDiACaNuPXJhLMiLVMSeyAYonyRPmbvJSterQ52HhCKgDkcR91C8nvjneag1e0eQ";
$expires_in = "3514";
$token_type = "bearer";

//ＷｅｂＡＰＩリクエスト
$method = "POST";
//$header = "Content-Type: application/json, Authorization: Bearer ".$access_token ;
$header = "Content-Type: application/x-www-form-urlencoded, Authorization: Bearer ".$access_token ;

$paginationKey = "";  //初回データ取得時は不要
$deviceCategory = 0; // ★必須 （血圧計：0、体組成計：1、歩数計：2、睡眠計：3、体温計：4、血糖計：5）
$deviceModel = "";
$deviceSerialID = "";
$userNumberInDevice = "";
$searchDateFrom = "1483196400000"; //ceil(microtime(true)*1000); //▲準必須
$searchDateTo = "1485874800000"; //ceil(microtime(true)*1000); //▲準必須
$searchDeviceDateFrom = "20170101000000"; //▲準必須
$searchDeviceDateTo = "20170102000000"; //▲準必須



// ログイン用のWebAPIをここに記載
$url = 'https://data-stg-jp.omronconnect.mobi/api/apps/'.$appid.'/server-code/versions/current/measureData';
$http_post_body = array(
//		"paginationKey" => "", //初回データ取得時は不要
		"deviceCategory" => $deviceCategory,
//		"deviceModel" => $deviceModel,
//		"deviceSerialID" => $deviceSerialID,
//		"userNumberInDevice" => $userNumberInDevice,
//		"searchDateFrom" => $searchDateFrom,
//		"searchDateTo" => $searchDateTo,
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

$result = http_post ($url, $http_post_body);
$response_json= json_decode ($result["content"],true);

?>






<!DOCTYPE html>
<html lang="ja">
    <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="description" content="OMRON connect Demo">
<head>
	<title>OGSC＆環境センサのデモ</title>
<!-- <link rel="stylesheet" href="css/style.css" /> -->

<!-- OGSC Cloud  -->
<link rel="stylesheet" href="css/BarGauge/jquery.BarGauge.css" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.SimpleChart.js"></script>
<script type="text/javascript" src="js/jquery.BarGauge.js"></script>

<!-- Nifty Cloud  -->
<script type="text/javascript" src="js/ncmb.min.js" charset="utf-8"></script>
<script type="text/javascript">

    //【環境センサー】Nifty mobile backendアプリとの連携
 function onKankyoButton1_Click(){


    // Nifty Cloud上のデータストアに接続
//    var ncmb = new NCMB("960534844e162f267e64764aa91ed32cbdf73a4582451afe5be15a6bec788bf6", "0072db3fd89db23a26cef2ef707c9f4f42035f8a8a69cc22dc7c881c49225934");
 	var ncmb = new NCMB("e34bf31c6652e31c561f3f0253bd13a46ace822c266a490e69d13c51109f0106", "1e58451ab1c41d53824514f79552c0a718716af91e898f8418494bf22132b416");
    var class_KankyoSensor = ncmb.DataStore("sensor_data");
//    var KS = new class_KankyoSensor();


    var target1 = document.getElementById("message1");
    target1.innerHTML = "接続成功";
    var target2 = document.getElementById("message2");
    var target3 = document.getElementById("message3");
    var target4 = document.getElementById("message4");
    var target5 = document.getElementById("message5");
    var target6 = document.getElementById("message6");
    var target7 = document.getElementById("message7");
    var target8 = document.getElementById("message8");

    var temper; //Temperature
    var humid; //Humidity
    var illumi; //Illuminance
    var uv; //UV
    var noize; //Noize
    var temphumid; // TemperatureHumidity
    var heat; //heatstroke
    var air; //Air Pressure


    class_KankyoSensor.equalTo("sensorId","1B-00-01-21") //SensorIdは埋め込み。
    .order("measureDate",true)  //降順にソート（昇順の場合は、第２引数をカット。）
    .limit(1)
    .fetchAll()
    .then(function(results){
       for (var i = 0; i < results.length; i++) {
         var object = results[i];

         temper = object.get("temperature");
         humid = object.get("humidity");
         illumi = object.get("illuminance");
         uv = object.get("uv");
         noize = object.get("noise");
         temphumid = object.get("temperatureHumidity");
         heat = object.get("heatStroke");
         air = object.get("airPressure");

         target1.innerHTML = temper.slice(0,temper.indexOf(',') );
         target2.innerHTML = humid.slice(0,humid.indexOf(','));
         target3.innerHTML = illumi.slice(0,illumi.indexOf(','));
         target4.innerHTML = uv.slice(0,uv.indexOf(','));
         target5.innerHTML = noize.slice(0,noize.indexOf(','));
         target6.innerHTML = temphumid.slice(0,temphumid.indexOf(','));
         target7.innerHTML = heat.slice(0,heat.indexOf(','));
         target8.innerHTML = air.slice(0,air.indexOf(','));

         $('#kion').BarGauge.value = 70;
         $('#kion').update();



       }
     })
    .catch(function(err){
       console.log(err);
       target.innerHTML = "データ取得失敗";
     });


 }

</script>

<script>
<!-- OGSC Cloud  -->
	header( "Content-Type: application/json; charset=utf-8" ) ;　// ＪＳＯＮ用にヘッダーを指定
</script>


<script>

//SimpleChart のデータ
$(document).ready(function(a) {
  var data = [{
  values:[
    {X:0,Y:122},
    {X:1,Y:127},
    {X:2,Y:125},
    {X:3,Y:134},
    {X:4,Y:124}
  ],
  color:"red",
  title:"max"
  },{
  values:[
    {X:0,Y:88},
    {X:1,Y:91},
    {X:2,Y:93},
    {X:3,Y:94},
    {X:4,Y:90}
  ],
  color:"blue",
  title:"min"
  },{
	  values:[
	    {X:0,Y:75},
	    {X:1,Y:74},
	    {X:2,Y:75},
	    {X:3,Y:77},
	    {X:4,Y:76}
	  ],
	  color:"orange",
	  title:"bpm"
  }];
  $('#BloodPressure').SimpleChart({
		data: data,					// Default plot
		lineWidth: 2,				// Chart Line Width
		strokeColor: "#333",		// Axis Lines Color
		borderColor: "#333",		// Border Color
		borderWidth: 3,				// Border Width
		backgroundColor: "#FFF",	// Background color of chart
		backgroundImg: "css/jquery.SimpleChart/monthly_grid.png", // Background Image for chart (i.e Grid Layout)
		title: "血圧",		        // Default Title of chart
		titleFontSize: 32,			// Title Font size
		titleColor: "#FFF",			// Title Color
		showTitle: true,			// If True show title
		titleBGColor: '#006',		// Title Background Color
		xTitle: "",				    // Title of X Axis
		yTitle: "",					// Title of Y Axis
		xTitleBGColor: '#006',		// Title X Axis background Color
		yTitleBGColor: '#006',		// Title Y Axis background Color
		xFontSize: 16,				// X Axis Title Font Size
		yFontSize: 16, 				// Y Axis Title Font Size
		xTitleColor: "#000",		// X Axis Title Color
		yTitleColor: "#000",		// Y Axis Title Color
		maxValX: 31,				// Maximum value of data XAxis
		maxValY: 200,				// Maximum value of data YAxis
		width: 500,					// Width of Graph
		height: 300,				// Height of Graph
		margin: 10,					// Margin between each graph
		showKey: true,				// Show chart key
		toolTip: ''					// If set, this will use jQuery Tooltip
  });
});


//BarGaugeを画面ロード時に初期化
$(document).ready(function(b) {
    $('#kion').BarGauge({
		value: 30,
		goal: 100,
		decPlaces: 2,
        color: '#ff0000',
		title: "気温",
		showTitle: true,
		value_before: "摂氏",
		value_after: "度C",
		valueColor: '#77ff77',
		showValue: true,
		animSpeed: 'slow',
		animType: 'linear',
		toolTip: '推移を表示'
	});
	$('#shitudo').BarGauge({
		value: 60,
		goal: 100,
		decPlaces: 2,
		color: '#00ff00',
		title: "湿度",
		showTitle: true,
		value_before: "湿度",
		value_after: "度",
		valueColor: '#77ff77',
		toolTip: '推移を表示',
		showValue: true,
		animSpeed: 1000,
		animType: 'swing',
		faceplate: "url(css/BarGauge/bar_graph-colorScale.png) no-repeat",
	});
	$('#syoudo').BarGauge({
		value: 2,
		goal: 10,
		color: 'yellow',
		backgroundColor: 'black',
		decPlaces: 0,
		title: "照度",
		toolTip: '推移を表示',
		valueColor: '#77ff77',
		showTitle: true,
		value_after: "％",
		showValue: true,
		animSpeed: 'fast',
		faceplate: "url(css/BarGauge/bar_graph-gradient.png) no-repeat"
	});
    $('#souon').BarGauge({
		value: 51,
		goal: 100,
		decPlaces: 2,
		color: 'pink',
		title: "騒音",
		toolTip: '推移を表示',
		valueColor: '#77ff77',
		showTitle: true,
		value_after: "デシベル",
		showValue: true,
		animSpeed: 'slow',
		animType: 'linear'
	});
    $('#uv').BarGauge({
		value: 51000,
		goal: 100000,
		decPlaces: 2,
		color: 'orange',
		title: "UV",
		toolTip: '推移を表示',
		valueColor: '#77ff77',
		showTitle: true,
		value_after: "度",
		showValue: true,
		animSpeed: 'slow',
		animType: 'linear'
	});
    $('#kiatsu').BarGauge({
		value: 100,
		goal: 10,
		decPlaces: 2,
		color: 'sky',
		title: "気圧",
		toolTip: '推移を表示',
		valueColor: '#77ff77',
		showTitle: true,
		value_before: "Pa",
		showValue: true,
		animSpeed: 'slow',
		animType: 'linear'
	});
    $('#fukai').BarGauge({
		value: 70,
		goal: 100,
		decPlaces: 2,
		color: 'white',
		backgroundColor: 'black',
		title: "不快指数",
		toolTip: '推移を表示',
		valueColor: '#77ff77',
		showTitle: true,
		showValue: true,
		animSpeed: 'slow',
		animType: 'linear'
	});
    $('#netyuusyou').BarGauge({
		value: 51,
		goal: 100,
		color: 'purple',
		backgroundColor: 'white',
		decPlaces: 2,
		title: "熱中症",
		toolTip: '推移を表示',
		valueColor: '#77ff77',
		showTitle: true,
		showValue: true,
		animSpeed: 'slow',
		animType: 'linear'
	});
});

</script>

</head>
<body>
	<table>
		<tr>
			<td>
				<h1 id = "message"><?php echo "OMRON connect Demo"; ?></h1>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td>
   <h1>血圧計</h1>
   <?php var_dump($result);?>>
   <div id="BloodPressure" style="position:relative"></div>
			</td>
		</tr>
		<tr>
			<td>
   <table>
   <h1>環境センサー</h1>

    <input type="button" id="btn1" value="テスト" onclick="onKankyoButton1_Click()" /><br>

      <tr>
      <td><font size=22 >気温</font></td><td><font size=22 color=blue><div id="message1"></div></font></td>
      <td><font size=22 >湿度</font></td><td><font size=22 color=blue><div id="message2"></div></font></td>
      </tr>
      <tr>
      <td><font size=22 >照度</font></td><td><font size=22 color=blue><div id="message3"></div></font></td>
      <td><font size=22 >騒音</font></td><td><font size=22 color=blue><div id="message4"></div></font></td>
      </tr>
      <tr>
      <td><font size=22 >UV</font></td><td><font size=22 color=blue><div id="message5"></div></font></td>
      <td><font size=22 >気圧</font></td><td><font size=22 color=blue><div id="message6"></div></font></td>
      </tr>
      <tr>
      <td><font size=22 >不快</font></td><td><font size=22 color=blue><div id="message7"></div></font></td>
      <td><font size=22 >熱中症</font></td><td><font size=22 color=blue><div id="message8"></div></font></td>
      </tr>


<!--
      <tr>
      <td><font size=22 >気温</font></td><td>
      <div id="kion" class="barGauge_container"></div></td>
      <td><font size=22 >湿度</font></td><td>
      <div id="shitudo" class="barGauge_container"></div></td>
      </tr>
      <tr>
      <td><font size=22 >照度</font></td><td>
      <div id="syoudo" class="barGauge_container"></div></td>
      <td><font size=22 >騒音</font></td><td>
      <div id="souon" class="barGauge_container"></div></td>
      </tr>
      <tr>
      <td><font size=22 >UV</font></td><td>
      <div id="uv" class="barGauge_container"></div></td>
      <td><font size=22 >気圧</font></td><td>
      <div id="kiatsu" class="barGauge_container"></div></td>
      </tr>
      <tr>
      <td><font size=22 >不快</font></td><td>
      <div id="fukai" class="barGauge_container"></div></td>
      <td><font size=22 >熱中症</font></td><td>
      <div id="netyuusyou" class="barGauge_container"></div></td>
      </tr>
      -->
    </table>

			</td>
		</tr>
	</table>

</body>
</html>
