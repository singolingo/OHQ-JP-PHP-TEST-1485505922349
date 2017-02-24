
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
function KankyoData(p1,p2,p3,p4,p5,p6,p7,p8,p9){
this.temper=p1;
this.humid=p2;
this.illumi=p3;
this.noize=p4;
this.uv=p5;
this.air=p6;
this.temphumid=p7;
this.heat=p8;
this.kankyo_date=p9;
}
var KSdata = [];


//【構造体】合体データ
function ALLdata(p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11,p11,p12){
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
var all = [];

////////////////////////////////////////////////////////////
//////////OMRON connectのデータを取得
//////////////////////////////////////////////////////////

//OMRON connect データの取得（min, max, bpm）
var OCdata = [];
OCdata[0] = new HealthData('2017/01/30 16:20',91,148,88);
OCdata[1] = new HealthData('2017/01/30 16:22',99,173,90);
OCdata[2] = new HealthData('2017/01/30 16:23',110,156,87);
OCdata[3] = new HealthData('2017/01/31 09:13',92,160,83);
OCdata[4] = new HealthData('2017/02/01 11:33',98,154,86);
OCdata[5] = new HealthData('2017/02/23 11:49',105,155,81);
OCdata[6] = new HealthData('2017/02/24 10:08',104,159,90);


//健康データの表示
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawOmronChart);



////////////////////////////////////////////////////////////
//////////チャート描画処理
//////////////////////////////////////////////////////////
function drawOmronChart() {  //本当は引数でデータを渡したいが、Google Chart の不具合?（外部JSライブラリのため？）で渡すとバグるので諦める。

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

function drawALLChart() {

	var data = new google.visualization.DataTable();
	data.addColumn('string','日付');
	data.addColumn('number','最低血圧');
	data.addColumn('number','最高血圧');
	data.addColumn('number','BPM');

	for (var i in ALLdata){
		data.addRows([[ALLdata[i].date, ALLdata[i].max, ALLdata[i].min, ALLdata[i].bpm]]);
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



////////////////////////////////////////////////////////////
//////////環境センサーのデータを取得（Nifty Mobile Backend）
//////////////////////////////////////////////////////////

// Nifty Cloud上のデータストアに接続
//【環境センサー】Nifty mobile backendアプリとの連携
function onLoadButton1_Click(){

    // Nifty Cloud上のデータストアに接続
 	var ncmb = new NCMB("e34bf31c6652e31c561f3f0253bd13a46ace822c266a490e69d13c51109f0106", "1e58451ab1c41d53824514f79552c0a718716af91e898f8418494bf22132b416");
    var class_KankyoSensor = ncmb.DataStore("sensor_data");
    var temper = []; //Temperature
    var humid = []; //Humidity
    var illumi = []; //Illuminance
    var uv = []; //UV
    var noize = []; //Noize
    var temphumid = []; // TemperatureHumidity
    var heat = []; //heatstroke
    var air = []; //Air Pressure
    var kankyo_date = []; // 計測日
    var battery; // 電池ボルト数

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


    //環境センサーのデータを取得
	class_KankyoSensor.equalTo("sensorId","1B-00-01-21") //SensorIdは埋め込み。
	.order("measureDate",true)  //降順にソート（昇順の場合は、第２引数をカット。）
	.limit(1)                   //先頭のカラムを取得。
	.fetchAll()
	.then(function(results){


        //環境センサーアプリ経由の最新情報を取得
        var object = results[0];
        temper = object.get("temperature").split(",");
        humid = object.get("humidity").split(",");
        illumi = object.get("illuminance").split(",");
        noize = object.get("noise").split(",");
        uv = object.get("uv").split(",");
        air = object.get("airPressure").split(",");
        temphumid = object.get("temperatureHumidity").split(",");
        heat = object.get("heatStroke").split(",");
        kankyo_date = object.get("measureDate").split(",");
        battery = object.get("batteryVoltage");

	    var	tmp = new KankyoData(temper,humid,illumi,noize,uv,air,temphumid,heat,kankyo_date,battery);

        //最新情報を表示
	    target1.innerHTML = temper[0];
		target2.innerHTML = humid[0];
		target3.innerHTML = illumi[0];
		target4.innerHTML = noize[0];
		target5.innerHTML = uv[0];
		target6.innerHTML = air[0];
		target7.innerHTML = temphumid[0];
		target8.innerHTML = heat[0];
		target9.innerHTML = kankyo_date[0];
		target10.innerHTML = battery;

	    ////////////////////////////////////////////////////////////
	    //////////マージ処理
	    //////////OMRON connect (OCdata) と 環境センサー (KSdata)
	    //////////でYYYY/MM/DD HH:MM が同じもののみコピー
	    //////////////////////////////////////////////////////////
	    //日付（YYYY/MM/DD HH が同じ場合のみコピー）
		var KStime=OCtime="";
        var k = 0;
	    for (var i = 0; i < temper.length; i++) {
　　　　　　KSdata[i] = new KankyoData(temper[i],humid[i],illumi[i],noize[i],uv[i],air[i],temphumid[i],heat[i],kankyo_date[i]);
			KStime = parseInt(KSdata[i].kankyo_date.substr(0,4) + KSdata[i].kankyo_date.substr(5,2) + KSdata[i].kankyo_date.substr(8,2) + KSdata[i].kankyo_date.substr(11,2) + KSdata[i].kankyo_date.substr(14,2),10);
			for(var j in OCdata){
		    	OCtime = parseInt(OCdata[j].date.substr(0,4) + OCdata[j].date.substr(5,2) + OCdata[j].date.substr(8,2) + OCdata[j].date.substr(11,2) + OCdata[j].date.substr(14,2),10);
	    		if(KStime == OCtime){
	    			all[k++] = new ALLdata(KSdata[i].kankyo_date, OCdata[j].max, OCdata[j].min, OCdata[j].bpm, KSdata[i].temper , KSdata[i].humid , KSdata[i].illumi , KSdata[i].noize , KSdata[i].uv , KSdata[i].air , KSdata[i].temphumid , KSdata[i].heat);
	    		}
	    		else
		    	{
		    		all[k++] = new ALLdata(KSdata[i].kankyo_date, 0, 0, 0, KSdata[i].temper , KSdata[i].humid , KSdata[i].illumi , KSdata[i].noize , KSdata[i].uv , KSdata[i].air , KSdata[i].temphumid , KSdata[i].heat);
		    	}
	    	}
		}

	    //チャート表示
	    for (i=0; i<all.length ; i++){
			if(all[i].max != 0){

				td.innerText = all[i].date;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].max;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].min;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].bpm;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].temper;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].humid;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].illumi;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].noize;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].uv;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].air;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].temphumid;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].heat;
	    	}
	    }

	     //ALLデータのチャートを表示
	    google.charts.load('current', {packages: ['corechart', 'line']});
	    google.charts.setOnLoadCallback(drawALLChart);


	    //データ一覧に値を代入する処理
        var  tr,td;
	    for (i=0; i<all.length ; i++){
		    	tr = ALLtbl.insertRow(-1);
	            tr.bgColor = '#ffffff';
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].date;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].max;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].min;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].bpm;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].temper;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].humid;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].illumi;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].noize;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].uv;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].air;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].temphumid;
	            td = tr.insertCell(-1);
		    	td.innerText = all[i].heat;
	    }


	})
    .catch(function(err){
       console.log(err);
       target1.innerHTML = "データ取得失敗";
     });



}　//End Of Function "onLoadButton1_Click"


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
     <font color=green><B>環境センサーと連携</B></font>：
     <input type="button" id="btn1" value="接続" onclick="onLoadButton1_Click()" />
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

	<table id="ALLtbl" border="2" bgcolor="#97defa">
		<tr bgcolor="#ffffff">
			<th bgcolor="#97defa">日付</th>
			<th bgcolor="#97defa">最高血圧</th>
			<th bgcolor="#97defa">最低血圧</th>
			<th bgcolor="#97defa">心拍</th>
			<th bgcolor="#97defa">温度</th>
			<th bgcolor="#97defa">湿度</th>
			<th bgcolor="#97defa">照度</th>
			<th bgcolor="#97defa">騒音</th>
			<th bgcolor="#97defa">ＵＶ</th>
			<th bgcolor="#97defa">気圧</th>
			<th bgcolor="#97defa">不快指数</th>
			<th bgcolor="#97defa">熱中症度</th>
		</tr>
	</table>



</body>
</html>
