<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="google-signin-client_id" content="426884727155-lkbio78170qu5qcaa7231ekdusuqfbnt.apps.googleusercontent.com">
    <title>Tweet on map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>
<body>

    <div class="container">
    
        <div style="display: flex">
            <div class="column">
                <img src="img/logo.png">
            </div>
            <div class="column">
                <input id="searchVal" class="input is-primary" type="text" placeholder="特定場所" value="Haneda International Airport" autofocus>
            </div>
            <div class="column is-2">
                <button class="button is-primary" id='searchBtn' style="width:100%;"><strong>Have Fun</strong></button>
            </div>
            <div class="column is-2 signIn">
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
            </div>
            <div class="column is-2 signOut">
                <button onclick="signOut();" class="button is-primary" id='searchBtn' style="width:100%;"><strong>Sign Out</strong></button>
            </div>
        </div>
        
        <div class="columns">
            <div class="column displayMap">
                <div id="map"></div>
            </div>
            <div class="column displayMap rightMap">
                <button class="routeBtn" onclick="animationPath();">Route</button>
                <div id="mapTimeline"></div>
            </div>
        </div>
        
    </div>


    <script>
        var isLogin = false
        var flightPlanCoordinates = [];
        var flightPath
        var map

        $(document).ready(function(){
            $('#searchBtn').click(function(){
                getGeoOfPlace()
            });
        });

        /*function animationPath(){
            console.log(flightPlanCoordinates)
            //flightPath.setMap(null)

            var lineSymbol = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                strokeColor: '#393'
            };

            flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                icons: [{
                    icon: lineSymbol,
                    offset: '100%'
                }],
                strokeColor: '#FF0000',
                strokeOpacity: 0.5,
                strokeWeight: 2
            });

            animateCircle(flightPath);

        }

        function animateCircle(line) {
            var count = 0;
            window.setInterval(function() {
                count = (count + 1) % 200;

                var icons = line.get('icons');
                icons[0].offset = (count / 2) + '%';
                line.set('icons', icons);
            }, 20);
        }*/

        function animationPath(){
            flightPath.setMap(null)

            let cnt = 0;
            var myInterval = setInterval(function(){
                cnt++
                insertPolyline();
                if(cnt == 10){
                    clearInterval(myInterval);
                }
            },200);

        }

        function insertPolyline(){
            var lineSymbol = {
                path: 'M 0,-1 0,1',
                scale: 5,
                strokeColor: '#FF0000'
            };

            flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                icons: [{
                    icon: lineSymbol,
                    offset: '0%'
                }],
                strokeColor: '#FFF',
                strokeOpacity: 1,
                strokeWeight: 1,
                map: map
            });
            animateCircle(flightPath);
        }

        function animateCircle(line) {
            var count = 0;
            window.setInterval(function() {
                count = (count + 1) % 200;

                var icons = line.get('icons');
                icons[0].offset = 100 - (count / 2) + '%';
                line.set('icons', icons);
            }, 20);
        }

    </script>

    <script src="js/auth.js"></script>
    <script src="js/googleMap.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_vlghBt3iKntZq1nVCR6uGVJuCk3hbdE"></script>
</body>
</html>