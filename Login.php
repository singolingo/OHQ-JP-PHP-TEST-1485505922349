<?php
Header( "HTTP/1.1 301 Moved Permanently" );

$url = 'https://data-stg-jp.omronconnect.mobi/api/apps/bdf72f34/oauth2/authorize?response_type=code&client_id=a9f0vfobhlh6n9c4q2q6d8v03al719kj5sh8&redirect_uri=https://ohq-jp-php-test.mybluemix.net/callback.php&scope=openid&state=sjp';
header("Location: {$url}");

exit;
?>