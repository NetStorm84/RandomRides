<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Random Routes</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
         integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <body>
        <div class="container">
          <h1>Random Rides <small>Motorcycle Route Generator</small></h1>
        </div>

        <div class="container">
          <p>Enter your Postcode below and we will provide a fantastic
            motorcycle route within the desire distance from your selected start point.</p>
        </div>

        <div class="container">
          <form class="form-inline" method="post" action="/route">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="postcode">Postcode</label>
              <input type="text" class="form-control" id="postcode"
                placeholder="BN10 6TG" name="postcode" required>
            </div>
            <div class="form-group">
              <label for="distance">Distance</label>
              <select class="form-control" name="distance">
                <option value="25">25 Miles</option>
                <option value="100">100 Miles</option>
                <option value="250">250 Miles</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Get Route</button>
          </form>
        </div>

        <div class="container">
          <div id="map"></div>
        </div>

        <script>

        @if(!empty($route))
          @foreach ($route as $rt)

            var route = {
              title : "{{$rt['title']}}",
              distance : "{{$rt['distance']}}"
            }

          @endforeach
        @endif

          function initMap() {
            var uluru = {lat: 51, lng: -1};
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 6,
              center: uluru
            });

            var routePath = [];

            @if(!empty($points))
              @foreach ($points as $point)

                routePath.push({
                  lng:{{$point->lng}},
                  lat:{{$point->lat}}
                });

              @endforeach

              var route = new google.maps.Polyline({
                path: routePath,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
              });

              route.setMap(map);

            @endif
          }
        </script>

        <script async defer
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOAeqsUX6LgDg7C2gTuvuLSfv1L8zfJdM&callback=initMap">
        </script>
    </body>
</html>
