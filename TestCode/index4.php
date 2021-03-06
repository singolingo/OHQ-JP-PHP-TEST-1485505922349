
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

<!-- Nifty Cloud  -->
<script type="text/javascript" src="js/ncmb.min.js" charset="utf-8"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    //【環境センサー】Nifty mobile backendアプリとの連携
 function onKankyoButton1_Click(){


    // Nifty Cloud上のデータストアに接続
//    var ncmb = new NCMB("960534844e162f267e64764aa91ed32cbdf73a4582451afe5be15a6bec788bf6", "0072db3fd89db23a26cef2ef707c9f4f42035f8a8a69cc22dc7c881c49225934");
 	var ncmb = new NCMB("e34bf31c6652e31c561f3f0253bd13a46ace822c266a490e69d13c51109f0106", "1e58451ab1c41d53824514f79552c0a718716af91e898f8418494bf22132b416");
    var class_KankyoSensor = ncmb.DataStore("sensor_data");
//    var KS = new class_KankyoSensor();


    var target1 = document.getElementById("message1");
    target1.innerHTML = "接続成功→読込中";
    var target2 = document.getElementById("message2");
    var target3 = document.getElementById("message3");
    var target4 = document.getElementById("message4");
    var target5 = document.getElementById("message5");
    var target6 = document.getElementById("message6");
    var target7 = document.getElementById("message7");
    var target8 = document.getElementById("message8");
    var target9 = document.getElementById("message9");
    var target10 = document.getElementById("message10");

    var temper; //Temperature
    var humid; //Humidity
    var illumi; //Illuminance
    var uv; //UV
    var noize; //Noize
    var temphumid; // TemperatureHumidity
    var heat; //heatstroke
    var air; //Air Pressure
    var kankyo_date; // 計測日
    var battery; // 電池ボルト数

////////// クラス定義　/////////////////////////////////////
    function KankyoData(p1,p2,p3,p4,p5,p6,p7,p8,p9,p10){
    	this.temper=p1;
    	this.humid=p2;
    	this.illumi=p3;
    	this.noize=p4;
    	this.uv=p5;
    	this.air=p6;
    	this.temphumid=p7;
    	this.heat=p8;
        this.kankyo_date=p9;
        this.battery=p10;  //配列ではない。
    }

    function BroodPressureData(p1,p2,p3,p4){
    	this.date=p1;
    	this.max=p2;
    	this.min=p3;
    	this.bpm=p4;

        }


//////////データ取得　/////////////////////////////////////

    //環境データの取込
class_KankyoSensor.equalTo("sensorId","1B-00-01-21") //SensorIdは埋め込み。
.order("measureDate",true)  //降順にソート（昇順の場合は、第２引数をカット。）
.limit(1)
.fetchAll()
.then(function(results){

        //チャート表示のために、配列でデータを取得。
        chart = new KankyoData;

        //先頭の値のみを参照。（練習のためforループ）
    	for (var i = 0; i < results.length; i++) {
        	var object = results[i];

         	temper = object.get("temperature");
         	humid = object.get("humidity");
         	illumi = object.get("illuminance");
         	noize = object.get("noise");
         	uv = object.get("uv");
         	air = object.get("airPressure");
         	temphumid = object.get("temperatureHumidity");
         	heat = object.get("heatStroke");
         	kankyo_date = object.get("measureDate");
         	battery = object.get("batteryVoltage");


	        if(i==0){
	        	chart = new KankyoData(temper,humid,illumi,noize,uv,air,temphumid,heat,kankyo_date,battery);
        	 	target1.innerHTML = chart.temper.slice(0,chart.temper.indexOf(',') );
         	 	target2.innerHTML = chart.humid.slice(0,chart.humid.indexOf(',') );
   	    	 	target3.innerHTML = chart.illumi.slice(0,chart.illumi.indexOf(','));
    	     	target4.innerHTML = chart.noize.slice(0,chart.noize.indexOf(',') );
    	     	target5.innerHTML = chart.uv.slice(0,chart.uv.indexOf(',') );
    	     	target6.innerHTML = chart.air.slice(0,chart.air.indexOf(',') );
    	     	target7.innerHTML = chart.temphumid.slice(0,chart.temphumid.indexOf(',') );
    	     	target8.innerHTML = chart.heat.slice(0,chart.heat.indexOf(',') );
    	     	target9.innerHTML = chart.kankyo_date.slice(0,chart.kankyo_date.indexOf(',') );
    	     	target10.innerHTML = chart.battery;
         	}
         }
     })
    .catch(function(err){
       console.log(err);
       target.innerHTML = "データ取得失敗";
     });
}

google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawChart);


function drawChart(bp_data) {
    var data = google.visualization.arrayToDataTable([
      ['日付', '最低血圧', '最高血圧', 'BPM'],
      ['1/30 16:20',91,148,88],
      ['1/30 16:22',99,173,90],
      ['1/30 16:23',110,156,87],
      ['1/31 9:13',92,160,83],
      ['2/1 11:33',98,154,86]

      ]);

    var options = {
      title: '血圧グラフ',
      curveType: 'function',
      width:900,
      height:500,
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    window.setTimeout(chart.draw(data, options), 7000);

  }



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

	<table width=500 align=left>
   <h1>血圧計</h1>
   <div id="curve_chart"><B><font size=128 color=red>読込中</font><B></div>
   </table>

   <table border=1>
   <h1>環境センサー</h1>
     <font color=red><B>更新ボタン</B></font>：
     <input type="button" id="btn1" value="接続" onclick="onKankyoButton1_Click()" />
      <tr>
      <td width=200><font size=16 >計測日</font></td><td width=200><font size=16 color=blue><div id="message9"></div></font></td>
      <td width=200><font size=16 >電池</font></td><td width=200><font size=16 color=blue><div id="message10"></div></font></td>
      </tr>
      <tr>
      <td width=200><font size=16 >気温</font></td><td width=200><font size=16 color=blue><div id="message1"></div></font></td>
      <td width=200><font size=16 >湿度</font></td><td width=200><font size=16 color=blue><div id="message2"></div></font></td>
      </tr>
      <tr>
      <td><font size=16 >照度</font></td><td><font size=16 color=blue><div id="message3"></div></font></td>
      <td><font size=16 >騒音</font></td><td><font size=16 color=blue><div id="message4"></div></font></td>
      </tr>
      <tr>
      <td><font size=16 >UV</font></td><td><font size=16 color=blue><div id="message5"></div></font></td>
      <td><font size=16 >気圧</font></td><td><font size=16 color=blue><div id="message6"></div></font></td>
      </tr>
      <tr>
      <td><font size=16 >不快</font></td><td><font size=16 color=blue><div id="message7"></div></font></td>
      <td><font size=16 >熱中症</font></td><td><font size=16 color=blue><div id="message8"></div></font></td>
      </tr>
    </table>

</body>
</html>
