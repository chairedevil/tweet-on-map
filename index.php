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
            <div class="column is-2">
                <div class="g-signin2" data-onsuccess="onSignIn"></div>
            </div>
        </div>
        
        <div class="columns">
            <div class="column displayMap">
                <div id="map"></div>
            </div>
            <div class="column displayMap">
                <div id="mapTimeline"></div>
            </div>
        </div>
        
    </div>


    <script>
        $(document).ready(function(){
            function onSignIn(googleUser) {
                var profile = googleUser.getBasicProfile();
                console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
                console.log('Name: ' + profile.getName());
                console.log('Image URL: ' + profile.getImageUrl());
                console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
            }


            $('#searchBtn').click(function(){

                getGeoOfPlace()
                
            });
        });

        function getGeoOfPlace(){
            searchVal = $('#searchVal').val()

            axios.get(`https://maps.googleapis.com/maps/api/geocode/json?address=${searchVal}&key=AIzaSyD_vlghBt3iKntZq1nVCR6uGVJuCk3hbdE`)
                .then(function (response) {
                    getNearlyTweet(response.data.results['0'].geometry.location.lat, response.data.results['0'].geometry.location.lng)
                })
        }

        function getNearlyTweet(lat, lng){
            console.log(`Geolocation:${lat}, ${lng}`)
            
            console.log(`Get Tweet Message`)
            axios.get(`lib/restServer.php?process=gettweetonlocate&lat=${lat}&lng=${lng}`)
                .then(function (response) {
                    showTweetOnMap(lat, lng , response.data.statuses)
                })

        }

        function showTweetOnMap(lat, lng, data){
            console.log(`Render map`)

            var uluru = {lat, lng};
            var map = new google.maps.Map(
                document.getElementById('map'),
                {
                    zoom: 16,
                    center: uluru
                }
            );
            var marker = new google.maps.Marker({position: uluru, map: map});

            let timeout = 0;

            data.forEach((tweet)=>{
                
                timeout = timeout + 20;
                window.setTimeout(function() {
                    if(tweet.geo){
                        let position = new google.maps.LatLng(tweet.geo.coordinates[0], tweet.geo.coordinates[1])
                        var marker = new google.maps.Marker({
                            position: position,
                            icon: tweet.user.profile_image_url,
                            map: map,
                            animation: google.maps.Animation.DROP,
                            title: tweet.user.name
                        });

                        let dateEdited = new Date(
                            tweet.created_at.replace(/^\w+ (\w+) (\d+) ([\d:]+) \+0000 (\d+)$/,
                            "$1 $2 $4 $3 UTC"));

                        let contentString = `<div class="card info-box">
                            <div class="card-content">
                                <div class="media">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                    <img src="${tweet.user.profile_image_url}">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <p class="title is-4">${tweet.user.name}</p>
                                    <p class="subtitle is-6">@${tweet.user.screen_name}</p>
                                </div>
                                </div>

                                <div class="content">
                                ${tweet.text}
                                <br><br>
                                <time datetime="2016-1-1">${dateEdited}</time>
                                </div>
                            </div>
                        </div>`
                        let infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });

                        marker.addListener('mouseover', ()=>{
                            infowindow.open(map, marker);
                        });
                        marker.addListener('mouseout', ()=>{
                            infowindow.close();
                        });
                        marker.addListener('click', ()=>{
                            getUserTimeline(tweet.user.screen_name)
                        });
                    }
                }, timeout);
            })

        }

        function getUserTimeline(userId){
            console.log(`Get ${userId}'s timeline`)

            axios.get(`lib/restServer.php?process=getusertimeline&user=${userId}`)
                .then(function ({data}) {
                    console.log(data)

                    var uluru = {"lat":36.562551, "lng":136.121074};
                    var map = new google.maps.Map(
                        document.getElementById('mapTimeline'),
                        {
                            zoom: 6,
                            center: uluru
                        }
                    );

                    var flightPlanCoordinates = [];
                    let firstCheckPoint = true;

                    data.forEach((tweet)=>{

                        if(tweet.geo){
                            if(firstCheckPoint){

                                let position = new google.maps.LatLng(tweet.geo.coordinates[0], tweet.geo.coordinates[1])
                                var marker = new google.maps.Marker({
                                    position: position,
                                    map: map,
                                    animation: google.maps.Animation.DROP
                                });

                                firstCheckPoint = false
                            }else{
                                
                                let position = new google.maps.LatLng(tweet.geo.coordinates[0], tweet.geo.coordinates[1])
                                var marker = new google.maps.Marker({
                                    position: position,
                                    map: map,
                                    animation: google.maps.Animation.DROP,
                                    icon: {
                                        path: google.maps.SymbolPath.CIRCLE,
                                        scale: 3
                                    },
                                });

                            }

                                let dateEdited = new Date(
                                    tweet.created_at.replace(/^\w+ (\w+) (\d+) ([\d:]+) \+0000 (\d+)$/,
                                    "$1 $2 $4 $3 UTC"));

                                let contentString = `<div class="card info-box">
                                    <div class="card-content">
                                        <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                            <img src="${tweet.user.profile_image_url}">
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-4">${tweet.user.name}</p>
                                            <p class="subtitle is-6">@${tweet.user.screen_name}</p>
                                        </div>
                                        </div>

                                        <div class="content">
                                        ${tweet.text}
                                        <br><br>
                                        <time datetime="2016-1-1">${dateEdited}</time>
                                        </div>
                                    </div>
                                </div>`
                                let infowindow = new google.maps.InfoWindow({
                                    content: contentString
                                });

                                marker.addListener('mouseover', ()=>{
                                    infowindow.open(map, marker);
                                });
                                marker.addListener('mouseout', ()=>{
                                    infowindow.close();
                                });

                            flightPlanCoordinates.push({"lat": tweet.geo.coordinates[0],"lng": tweet.geo.coordinates[1]})
                        }

                    })

                    for(let i = data.length-1 ; i>=0 ; i--){
                        if(data[i].geo){
                            let image = {
                                    url: 'img/past.png'
                                };
                            let position = new google.maps.LatLng(data[i].geo.coordinates[0], data[i].geo.coordinates[1])
                            var marker = new google.maps.Marker({
                                position: position,
                                icon: image,
                                map: map,
                                animation: google.maps.Animation.DROP
                            });
                            break
                        }
                    }

                    var flightPath = new google.maps.Polyline({
                        path: flightPlanCoordinates,
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    });
                    flightPath.setMap(map);

                })
        }

    </script>

    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_vlghBt3iKntZq1nVCR6uGVJuCk3hbdE"></script>
</body>
</html>