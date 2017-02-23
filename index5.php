
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

 <!--Load the Google API-->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<script type="text/javascript">

////////////////////////////////////////////////////////////
//////////クラス定義　/////////////////////////////////////
//////////////////////////////////////////////////////////

//【構造体】健康データ
function HealthData(p1,p2,p3,p4){
	this.date=p1;
	this.max=p2;
	this.min=p3;
	this.bpm=p4;

  }

//【構造体】環境データ
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

//【構造体】合体データ
function ALLData(p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11,p11){
	this.date=p1;
	this.max=p2;
	this.min=p3;
	this.bpm=p4;
	this.temper=p5;
	this.humid=p6;
	this.illumi=p7;
	this.noize=p8;
	this.uv=p9;
	this.air=p10;
	this.temphumid=p11;
	this.heat=p12;

  }




////////////////////////////////////////////////////////////
//////////データ取得　/////////////////////////////////////
//////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
//////////環境センサーのデータを取得（Nifty Mobile Backend）
//////////////////////////////////////////////////////////
    // Nifty Cloud上のデータストアに接続
 	var ncmb = new NCMB("e34bf31c6652e31c561f3f0253bd13a46ace822c266a490e69d13c51109f0106", "1e58451ab1c41d53824514f79552c0a718716af91e898f8418494bf22132b416");
    var class_KankyoSensor = ncmb.DataStore("sensor_data");
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

    KSdata = [];

    var target1 = document.getElementById("message1");
    var target2 = document.getElementById("message2");
    var target3 = document.getElementById("message3");
    var target4 = document.getElementById("message4");
    var target5 = document.getElementById("message5");
    var target6 = document.getElementById("message6");
    var target7 = document.getElementById("message7");
    var target8 = document.getElementById("message8");
    var target9 = document.getElementById("message9");
    var target10 = document.getElementById("message10");

    class_KankyoSensor.equalTo("sensorId","1B-00-01-21") //SensorIdは埋め込み。
    .order("measureDate",true)  //降順にソート（昇順の場合は、第２引数をカット。）
    .limit(1)
    .fetchAll()
    .then(function(result){

        target1.innerHTML = "接続成功→読込中";

        //最新の環境データを参照（配列＝０）
    	for (var i = 0; i < result.length; i++) {

    		var object = result[i];

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

	    	KSdata[i] = new KankyoData(temper,humid,illumi,noize,uv,air,temphumid,heat,kankyo_date,battery);

	    	//最新の環境データを取得(GLOBAL)
         	if(i==0){
        		 target1.innerHTML = KSdata.temper.slice(0,KSdata.temper.indexOf(',') );
        	 	 target2.innerHTML = KSdata.humid.slice(0,KSdata.humid.indexOf(',') );
   	    		 target3.innerHTML = KSdata.illumi.slice(0,KSdata.illumi.indexOf(','));
    		     target4.innerHTML = KSdata.noize.slice(0,KSdata.noize.indexOf(',') );
    		     target5.innerHTML = KSdata.uv.slice(0,KSdata.uv.indexOf(',') );
    		     target6.innerHTML = KSdata.air.slice(0,KSdata.air.indexOf(',') );
    		     target7.innerHTML = KSdata.temphumid.slice(0,KSdata.temphumid.indexOf(',') );
    		     target8.innerHTML = KSdata.heat.slice(0,KSdata.heat.indexOf(',') );
    		     target9.innerHTML = KSdata.kankyo_date.slice(0,KSdata.kankyo_date.indexOf(',') );
    		     target10.innerHTML = KSdata.battery ;
        	 }

       }
     })
    .catch(function(err){
       console.log(err);
      // target1.innerHTML = "データ取得失敗";
     });



////////////////////////////////////////////////////////////
//////////OMRON connectのデータを取得
//////////////////////////////////////////////////////////

//健康データの取得（仮）
var OCdata = [];
OCdata[0] = new HealthData('2017/01/30 16:20',91,148,88);
OCdata[1] = new HealthData('2017/01/30 16:22',99,173,90);
OCdata[2] = new HealthData('2017/01/30 16:23',110,156,87);
OCdata[3] = new HealthData('2017/01/31 09:13',92,160,83);
OCdata[4] = new HealthData('2017/02/01 11:33',98,154,86);

//Load the Visualization API and the corechart package.
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawChart);



////////////////////////////////////////////////////////////
//////////マージ処理
//////////OMRON connect (OCdata) と 環境センサー (KSdata)
//////////でYYYY/MM/DD HH:MM が同じもののみコピー
//////////////////////////////////////////////////////////
var all = [];
var year=month=day=hour=min=0;
var KStime=OCtime=0;
var k = 0;
//日付（YYYY/MM/DD HH:MM が同じ場合のみコピー）
//KSdata  '2017/02/02 09:36:06'
//OCdata  '2017/01/30 16:20'
for(var i in OCdata){
	//year = parseInt(OCdata[i].substr(0,4),10);
	//     month = parseInt(OCdata[i].substr(6,2),10);
	//     day = parseInt(OCdata[i].substr(9,2),10);
	//    hour = parseInt(OCdata[i].substr(12,2),10);
	//   min = parseInt(OCdata[i].substr(15,2),10);
	OCtime = parseInt(OCdata[i].date.substr(0,4) + OCdata[i].date.substr(5,2) + OCdata[i].date.substr(8,2) + OCdata[i].date.substr(11,2) + OCdata[i].date.substr(14,2),10);
	for(var j in KSdata){
		KStime = parseInt(KSdata[j].kankyo_date.substr(0,4) + KSdata[j].kankyo_date.substr(5,2) + KSdata[j].kankyo_date.substr(8,2) + KSdata[j].kankyo_date.substr(11,2) + KSdata[j].kankyo_date.substr(14,2),10);
		if(OCtime.date.equals(KStime) == true){
			all[k++] = new ALLdata(OCdata[i].date, OCdata[i].max, OCdata[i].min, OCdata[i].bpm, KSdata[i].temper , KSdata[i].humid , KSdata[i].illumi , KSdata[i].noize , KSdata[i].uv , KSdata[i].air , KSdata[i].temphumid , KSdata[i].heat);
		}
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////// ファンクション
////////////////////////////////////////////////////////////////////////////////////////////////////////////

function drawChart() {  //本当は引数でデータを渡したいが、Google Chart の不具合（外部JSライブラリのため？）で渡すとバグるので諦める。

	var data = new google.visualization.DataTable();
	data.addColumn('string','日付');
	data.addColumn('number','最低血圧');
	data.addColumn('number','最高血圧');
	data.addColumn('number','BPM');

	for (var i in OCdata){
		data.addRows([[OCdata[i].date, OCdata[i].max, OCdata[i].min, OCdata[i].bpm]]);
	}

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
