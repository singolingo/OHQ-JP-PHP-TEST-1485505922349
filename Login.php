<?php
//PHP でログインする場合
//Header( "HTTP/1.1 301 Moved Permanently" );
$url = 'https://data-stg-jp.omronconnect.mobi/api/apps/bdf72f34/oauth2/authorize?response_type=code&client_id=a9f0vfobhlh6n9c4q2q6d8v03al719kj5sh8&redirect_uri=https://ohq-jp-php-test.mybluemix.net/callback.php&scope=openid&state=sjp';
//header('Location: {$url}');
//exit();
?>

<!DOCTYPE html>
<html lang = “ja”>
<head>
<meta charset = “UFT-8”>
<title>OGSC クラウドから、コールバックで返却される値を取得。</title>


<script type="text/javascript">

 function onLoginButton1_Click(){

    	window.location.href = "<?php print ($url);?>>";

 }


</script>


</head>
<body>
<h1>ＳＴＥＰ１：ＯＧＳＣバイタルデータへのアクセス承認画面</h1>
  <input type="button" id="btn1" value="私のバイタルデータに参照することに同意します。" onclick="onLoginButton1_Click();" /><br>

</body>
</html>






