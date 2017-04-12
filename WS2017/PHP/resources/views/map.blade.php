<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">

        <title>気象情報</title>

        {{-- <script src="/js/app.js"></script> --}}
        <script src="js/chart.js"></script>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet" type="text/css">
    </head>
    <body>
      <div class="container">
        <div class="row">
          <div class="col-sm-3 sidebar">
            <div class="graph portrait">
              <canvas id="graph-portrait"></canvas>
            </div>
            <div class="graph landscape">
              <canvas id="air-pressure"></canvas>
            </div>
            <div class="graph landscape">
              <canvas id="air-temperature"></canvas>
            </div>
            <div class="weather">
              {{ $weather['location'] }}
              <div class="flexiblebox">
                <div class="icon">
                  <img src="{{ $weather['icon'] }}">
                </div>
                <div>
                  <div class="value">{{ $weather['property'] }}</div>
                  <div>{{ $weather['value'] }} {{ $weather['symbol'] }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-9">
            <div id="map"></div>
          </div>
          <script>
            function initMap() {
              // Create a map object and specify the DOM element for display.
//koko
//本番
//              var center = new google.maps.LatLng(35.630084687277055, 139.73297238349915);
              var center = new google.maps.LatLng(35.629151605002825, 139.74415183067322);
              var map = new google.maps.Map(document.getElementById('map'), {
                center: center,
                mapTypeId: google.maps.MapTypeId.HYBRID,
                scrollwheel: false,
                gestureHandling: 'greedy',
                zoom: 16
              });

              var currentLocationMarker = new google.maps.Marker({
                position: center,
                icon: {
                  path: google.maps.SymbolPath.CIRCLE,
                  scale: 7,
                  strokeColor: '#FFFFFF',
                  strokeOpacity: 1,
                  strokeWeight: 2,
                  fillColor: '#4285f4',
                  fillOpacity: 1
                },
                draggable: false,
                map: map
              });

              var contentString = (function() {/*
                @include('partial.weatherinfo', $current_data)
              */}).toString().match(/\/\*([^]*)\*\//)[1];

              var infowindow = new google.maps.InfoWindow({
                content: contentString
              });

              currentLocationMarker.addListener('click', function() {
               infowindow.open(map, currentLocationMarker);
              });

              infowindow.open(map, currentLocationMarker);
            }

            var data = {
              air_pressure: [
                @foreach ($graph_data as $data)
                  {x: new Date("{!! $data['date'] !!}"), y: {!! $data['air_pressure'] !!} },
                @endforeach
              ],
              air_temperature: [
                @foreach ($graph_data as $data)
                  {x: new Date("{!! $data['date'] !!}"), y: {!! $data['air_temperature'] !!} },
                @endforeach
              ],
              danger: [
                @foreach ($danger as $date)
                {
                  x: new Date("{!! $date['date'] !!}"),
                  y: 1020
                },
                @endforeach
              ]
            }

            var canvasAirPressure = document.getElementById("air-pressure");
            var ctxAirPressure = canvasAirPressure.getContext("2d");

            canvasAirPressure.height = canvasAirPressure.parentElement.clientHeight * 0.9;

            var canvasAirTemperature = document.getElementById("air-temperature");
            var ctxAirTemperature = canvasAirTemperature.getContext("2d");

            canvasAirTemperature.height = canvasAirTemperature.parentElement.clientHeight * 0.9;

            var canvasPortrait = document.getElementById("graph-portrait");
            var ctxPortrait = canvasPortrait.getContext("2d");

            var tmp = document.createElement('div');
            tmp.style.height = '30vh';
            canvasPortrait.height = tmp.clientHeight;

            var airPressureChart = new Chart(ctxAirPressure, {
              type: 'line',
              data: {
                datasets: [
                  {
                    label: '気圧',
                    yAxisID: 'air_pressure',
                    xAxisID: 'date',
                    data: data.air_pressure,
                    backgroundColor: 'rgba(255, 255, 255, 1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false,
                    borderWidth: 1
                  },
                  {
                    label: '要注意',
                    yAxisID: 'air_pressure',
                    xAxisID: 'date',
                    data: data.danger,
                    backgroundColor: 'rgba(255, 187, 0, .5)',
                    borderColor: 'rgba(54, 162, 235, 0)',
                    fill: true,
                    pointRadius: 0,
                    borderWidth: 0
                  }
                ]
              },
              options: {
                scales: {
                  yAxes: [
                    {
                      id: 'air_pressure',
                      ticks: {
                        fontColor: '#ff6384',
                        beginAtZero:false
                      }
                    }
                  ],
                  xAxes: [{
                    id: 'date',
                    type: 'time',
                    time: {
                      unit: 'hour',
                      unitStepSize: 6
                    },
                    position: 'bottom'
                  }]
                },
                animation: {
                  duration: 0
                },
                verticalLine: [{
                  x: new Date(),
                  style: 'rgba(255, 0, 0, .4)',
                  text: 'now'
                }]
              }
            });

            var airTemperatureChart = new Chart(ctxAirTemperature, {
              type: 'line',
              data: {
                datasets: [
                  {
                    label: '気温',
                    yAxisID: 'air_temperature',
                    xAxisID: 'date',
                    data: data.air_temperature,
                    backgroundColor: 'rgba(255, 255, 255, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false,
                    borderWidth: 1
                  }
                ]
              },
              options: {
                scales: {
                  yAxes: [
                    {
                      id: 'air_temperature',
                      labelString: '温度',
                      ticks: {
                        fontColor: '#36a2eb',
                        beginAtZero: false
                      }
                    }
                  ],
                  xAxes: [{
                    id: 'date',
                    type: 'time',
                    time: {
                      unit: 'hour',
                      unitStepSize: 6
                    },
                    position: 'bottom'
                  }]
                },
                animation: {
                  duration: 0
                },
                verticalLine: [{
                  x: new Date(),
                  style: 'rgba(255, 0, 0, .4)',
                  text: 'now'
                }]
              }
            });

            var portraitChart = new Chart(ctxPortrait, {
              type: 'line',
              data: {
                datasets: [
                  {
                    label: '気圧',
                    yAxisID: 'air_pressure',
                    xAxisID: 'date',
                    data: data.air_pressure,
                    backgroundColor: 'rgba(255, 255, 255, 1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false,
                    borderWidth: 1
                  },
                  {
                    label: '気温',
                    yAxisID: 'air_temperature',
                    xAxisID: 'date',
                    data: data.air_temperature,
                    backgroundColor: 'rgba(255, 255, 255, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false,
                    borderWidth: 1
                  },
                  {
                    label: '要注意',
                    yAxisID: 'air_pressure',
                    xAxisID: 'date',
                    data: data.danger,
                    backgroundColor: 'rgba(255, 187, 0, .5)',
                    borderColor: 'rgba(54, 162, 235, 0)',
                    fill: true,
                    pointRadius: 0,
                    borderWidth: 0
                  }
                ]
              },
              options: {
                scales: {
                  yAxes: [
                    {
                      id: 'air_pressure',
                      ticks: {
                        fontColor: '#ff6384',
                        beginAtZero:false
                      }
                    },
                    {
                      id: 'air_temperature',
                      labelString: '温度',
                      position: 'right',
                      ticks: {
                        fontColor: '#36a2eb',
                        beginAtZero: false
                      }
                    }
                  ],
                  xAxes: [{
                    id: 'date',
                    type: 'time',
                    time: {
                      unit: 'hour',
                      unitStepSize: 6
                    },
                    position: 'bottom',
                    paddingBottom: 20
                  }]
                },
                animation: {
                  duration: 0
                },
                verticalLine: [{
                  x: new Date(),
                  style: 'rgba(255, 0, 0, .4)',
                  text: 'now'
                }]
              }
            });

            document.addEventListener(
              'touchmove',
              function(e) {
                e.preventDefault();
              },
                false
            );
          </script>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSTjTSK_9zXTKg6MiVHZbVeulPdzUbxBI&callback=initMap" async defer></script>
    </body>
</html>
