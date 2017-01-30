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


// 	var ncmb = new NCMB("e34bf31c6652e31c561f3f0253bd13a46ace822c266a490e69d13c51109f0106", "1e58451ab1c41d53824514f79552c0a718716af91e898f8418494bf22132b416");

    // Nifty Cloud上のデータストアに接続
    var ncmb = new NCMB("960534844e162f267e64764aa91ed32cbdf73a4582451afe5be15a6bec788bf6", "0072db3fd89db23a26cef2ef707c9f4f42035f8a8a69cc22dc7c881c49225934");
    var OC_KankyoSensor = ncmb.DataStore("TestClass_01");


    OC_KankyoSensor.set("FirstName","Test_Firstname")
    	    .set("LastName","Test_Lastname")
    	    .set("Age","20")
    	    .save()
    	    .then(function(){
	         target.innerHTML = "登録成功";
	        })
	        .catch(function(error){
    	     target.innerHTML = "Ｅｒｒｏｒ";
    	    });


//【環境センサー】Nifty mobile backendアプリとの連携
function onKankyoButton1_Click(){
        target = document.getElementById("message1");
        target.innerHTML = "接続成功";

 }



</script>



</head>
<body>

   <table>
   <h1>環境センサー</h1>

    <input type="button" id="btn1" value="テスト" onclick="onKankyoButton1_Click()" /><br>
    <div id="message1">ボタンを押してください。</div><br>


    </table>

	</table>

</body>
</html>
