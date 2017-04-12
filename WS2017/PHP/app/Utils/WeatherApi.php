<?php
namespace App\Utils;

use Log;

class WeatherApi {
    public static function fetch() {
        $curl = curl_init();

//        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, config('weather.username') . ':' . config('weather.password'));

        curl_setopt($curl, CURLOPT_URL, config('weather.api'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);

// テスト用
        //$latitude = 35.630084687277055;
        //$longitude = 139.73297238349915;
        $latitude = 35.629151605002825;
        $longitude = 139.74415183067322;

        $post_data = array('latitude' => $latitude ,'longitude'=> $longitude);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // $result = false;

        curl_close($curl);

        if ($result === false || $status !== 200) {
            Log::info('API error. Use default value.');
            $result = config('weather.default_value');
        }

        $data = json_decode($result, true);

        return $data;
    }
}
