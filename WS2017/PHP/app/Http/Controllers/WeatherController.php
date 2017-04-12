<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\WeatherApi;
use Log;
use Datetime;
use DateInterval;

use DB;

class WeatherController extends Controller
{
    public function index() {
        Log::debug('WeatherController index');
        $weather_data = WeatherApi::fetch();

        // DBのデータを取得する。
        $records = DB::select('select * from watson_summit_demo where id = ?',[1]);
        $refresh_count = 0;
        foreach ($records as $rec) {
            $refresh_count = $rec->refresh_count;
            $is_danger = $rec->is_danger;
        }

        // 画面をN回リフレッシュするごとに、要注意状態を切り替える。
        if ($is_danger != 1) {
            if ($refresh_count >= 3) {
                $refresh_count = 0;
                $is_danger = 1;
            } else {
                $refresh_count += 1;
            }
        } else {
            if ($refresh_count >= 5) {
                $refresh_count = 0;
                $is_danger = 0;
            } else {
                $refresh_count += 1;
            }
        }

        // DBを更新する。
        $air_temperature = $weather_data['temp'];  // 気温
        $relative_humidity = $weather_data['rh'];  // 湿度
        $illuminance = 16.0;                       // 照度
        $noise = 39.6;                             // 騒音
        $uv = $weather_data['uv_desc'];            // UV
        $air_pressure = $weather_data['pressure']; // 気圧
        $discomfort_index = '暑くない';            // 不快指数
        $heatstroke = '注意';                      // 熱中症

        DB::update('update watson_summit_demo set refresh_count = ?, is_danger = ?, air_temperature = ?, relative_humidity = ?, illuminance = ?, noise = ?, uv = ?, air_pressure = ?, discomfort_index = ?, heatstroke = ? where id = ?',
            [$refresh_count, $is_danger, $air_temperature, $relative_humidity, $illuminance, $noise, $uv, $air_pressure, $discomfort_index, $heatstroke, 1]);

        // 現在のWeather Dataを取得
        $graph_data = $this->getCurrentWeatherData($weather_data);

        $data = [
            // お天気アイコンや気温の表示
            'weather' => [
                'icon' => 'images/weathericons/icon' . $weather_data['wx_icon'] . '.png',
                'location' => 'Tokyo/Haneda',
                'property' => 'Temperature',
                'value' => $weather_data['temp'],
                'symbol' => '℃'
            ],
            // グラフ
            'graph_data' => $graph_data['data'],
            // 警告
            'danger' => $graph_data['danger'],
            // 吹き出しのデータ
            'current_data' => [
                'air_temperature' => [
                    'label' => '気温',
                    'icon' => 'wi wi-thermometer',
                    'value' => $weather_data['temp'],
                    'symbol' => '℃'
                ],
                'relative_humidity' => [
                    'label' => '湿度',
                    'icon' => 'wi wi-humidity',
                    'value' => $weather_data['rh'],
                    'symbol' => '%'
                ],
                'illuminance' => [
                    'label' => '照度',
                    'icon' => 'fa fa-lightbulb-o',
                    'value' => 16.0,
                    'symbol' => 'lx'
                ],
                'noise' => [
                    'label' => '騒音',
                    'icon' => 'fa fa-volume-up',
                    'value' => 39.6,
                    'symbol' => 'db'
                ],
                'uv' => [
                    'label' => 'UV',
                    'icon' => 'fa fa-sun-o',
                    'value' => $weather_data['uv_desc'],
                    'symbol' => ''
                ],
                'air_pressure' => [
                    'label' => '気圧',
                    'icon' => 'wi wi-barometer',
                    'value' => $weather_data['pressure'],
                    'symbol' => 'hPa'
                ],
                'discomfort_index' => [
                    'label' => '不快指数',
                    'icon' => 'fa fa-heart-o',
                    'value' => '暑くない',
                    'symbol' => ''
                ],
                'heatstroke' => [
                    'label' => '熱中症',
                    'icon' => 'wi wi-hot',
                    'value' => '注意',
                    'symbol' => ''
                ]
            ]
        ];


        return view('map', $data);
    }


    public function test() {
        $data = $this->getCurrentWeatherData();

        return response($data);
    }


    // 現在のWeatherDataを取得
    public function getCurrentWeatherData($weather_data) {
        $now = new DateTime('NOW');

        $start = clone $now;
        $end = clone $now;

        $start->sub(new DateInterval('PT6H'));
        $end->add(new DateInterval('P1D'));

        return $this->getWeatherData($start, $end, $now, $weather_data);
    }


    // 時間範囲指定でWeatherDataを取得
    public function getWeatherData($start, $end, $now, $weather_data) {
        $path = base_path() . '/resources/data/weather.json';
        $string = file_get_contents($path);
        $json = json_decode($string, true);

        $time1 = clone $now;
        $time2 = clone $now;
        $time3 = clone $now;
        $time4 = clone $now;
        $time1->add(new DateInterval('PT2H'));
        $time2->add(new DateInterval('PT4H'));
        $time3->add(new DateInterval('PT8H'));
        $time4->add(new DateInterval('PT24H'));

        // DBから要注意状態を取得する。
        $records = DB::select('select * from watson_summit_demo where id = ?', [1]);
        $is_danger = 0;
        foreach ($records as $rec) {
            $is_danger = $rec->is_danger;
        }

        $response = [];
        $response['danger'][] = ['date' => $time1->format('Y/m/d H:00:00')];

        $law = 0;

        // 実際の気圧をもとにグラフデータを補正
        $now_val = $weather_data['pressure'];
        $dat_val = 0;
        $dif_val = 0;

        foreach ($json as $index => $value) {
            $date = new Datetime($value['date']);
            if ($start <= $date && $date <= $end) {
                $next = clone $date;
                $next->add(new DateInterval('PT1H'));
                if ($date <= $now && $now <= $next) {
                    $dat_val = $json[$index]['air_pressure'];
                }
            }
        }

        if ($dat_val != 0) {
            $dif_val = $now_val - $dat_val;
            foreach ($json as $index => $value) {
                $date = new Datetime($value['date']);
                if ($start <= $date && $date <= $end) {
                    $json[$index]['air_pressure'] = $json[$index]['air_pressure'] + $dif_val;
                }
            }
        }

        // 実際の気温をもとにグラフデータを補正
        $now_val = $weather_data['temp'];
        $dat_val = 0;
        $dif_val = 0;

        foreach ($json as $index => $value) {
            $date = new Datetime($value['date']);
            if ($start <= $date && $date <= $end) {
                $next = clone $date;
                $next->add(new DateInterval('PT1H'));
                if ($date <= $now && $now <= $next) {
                    $dat_val = $json[$index]['air_temperature'];
                }
            }
        }

        if ($dat_val != 0) {
            $dif_val = $now_val - $dat_val;
            foreach ($json as $index => $value) {
                $date = new Datetime($value['date']);
                if ($start <= $date && $date <= $end) {
                    $json[$index]['air_temperature'] = $json[$index]['air_temperature'] + $dif_val;
                }
            }
        }

        // グラフに表示すべくデータを生成する
        foreach ($json as $index => $value) {
            $date = new Datetime($value['date']);
            // 表示範囲のデータが対象
            if ($start <= $date && $date <= $end) {

              if ($is_danger == 1) { // 要注意時は、急降下グラフに補正する

                if ($time1 < $date && $date < $time2) {
                    $json[$index]['air_pressure'] = $json[$index - 1]['air_pressure'] - 3;
                    $response['data'][] = $json[$index];
                    $law = $json[$index]['air_pressure'];
                    $response['danger'][] = ['date' => $date->format('Y/m/d H:00:00')];
                } else if ($time2 < $date && $date < $time3) {
                    $json[$index]['air_pressure'] = $law + (lcg_value() - 0.2);
                    $response['data'][] = $json[$index];
                } else if ($time3 < $date && $date < $time4) {
                    $json[$index]['air_pressure'] = $json[$index - 1]['air_pressure'] + (lcg_value() - 0.2);
                    $response['data'][] = $json[$index];
                } else {
                    $response['data'][] = $value;
                }

              } else { // 通常は、こちらの値を採用
                    $response['data'][] = $value;
              }

            }
        }

        // $response['danger'][] = ['date' => $time2->format('Y/m/d H:00:00')];

        return $response;
    }


    // iOSデモアプリから受信したデータを反映し、要注意状態、および要注意メッセージを返信する。
    public function alert() {

/* TEST中
        // パラメータから位置情報を取得
        $latitude = Request::input('latitude');
        $longitude = Request::input('longitude');

        // DBの位置情報を更新
        if ($latitude != 0 && $longitude != 0) {
            DB::update('update watson_summit_demo set latitude = ?, longitude = ? where id = ?',
                [$latitude, $longitude, 1]);
        }
*/

        // DBの項目を取得
        $records = DB::select('select * from watson_summit_demo where id = ?', [1]);
        $is_danger = 0;
        $air_temperature = 0;
        $relative_humidity = 0;
        $illuminance = 0;
        $noise = 0;
        $uv = '';
        $air_pressure = 0;
        $discomfort_index = '';
        $heatstroke = '';
        foreach ($records as $rec) {
            $is_danger = $rec->is_danger;                  // 要注意状態
            $air_temperature = $rec->air_temperature;      // 気温
            $relative_humidity = $rec->relative_humidity;  // 湿度
            $illuminance = $rec->illuminance;              // 照度
            $noise = $rec->noise;                          // 騒音
            $uv = $rec->uv;                                // UV
            $air_pressure = $rec->air_pressure;            // 気圧
            $discomfort_index = $rec->discomfort_index;    // 不快指数
            $heatstroke = $rec->heatstroke;                // 熱中症
        }

        // iOS側への返信情報を生成
        $alert_message = '';
        if ($is_danger != 0) {
            $alert_message = '気象条件の悪化により、足立さんの喘息が悪化する可能性があります。気をつけてください。';
        }

        $rep = array(
            "is_danger" => $is_danger,                  // 要注意状態
            "air_temperature" => $air_temperature,      // 気温
            "relative_humidity" => $relative_humidity,  // 湿度
            "illuminance" => $illuminance,              // 照度
            "noise" => $noise,                          // 騒音
            "uv" => $uv,                                // UV
            "air_pressure" => $air_pressure,            // 気圧
            "discomfort_index" => $discomfort_index,    // 不快指数
            "heatstroke" => $heatstroke,                // 熱中症
            "alert_message" => $alert_message,
        );

        $json_out = json_encode($rep);

        return response($json_out);
    }


}
?>
