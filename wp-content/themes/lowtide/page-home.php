<?php get_header(); ?>

<main id="content">
  <div class="hero">
    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop" poster="<?php echo get_template_directory_uri(); ?>/img/hero/dock.jpg" tabindex="-1">
      <source src="<?php echo get_template_directory_uri(); ?>/img/hero/dock.webm" type="video/webm">
      <source src="<?php echo get_template_directory_uri(); ?>/img/hero/dock.mp4" type="video/mp4">
    </video>

    <div class="container">
      <div class="col-md-9">
        <h1>Smart sea level sensors in Chatham County, GA</h1>
        <p>The Smart Sea Level Sensors project is a partnership between Chatham Emergency Management Agency officials, City of Savannah officials, and Georgia Tech scientists and engineers who are working together to install a network of internet-enabled sea level sensors across Chatham County. The real-time data on coastal flooding will be used for emergency planning and response.</p>
        <div class="learn-more">
          <p><a href="#get-involved" class="btn-cta">Get involved</a></p>
          <p><a href="https://www.youtube.com/watch?v=J6mO_q-qExA" target="_blank" class="btn-cta-secondary" aria-label="Sea level rise in Savannah youtube video">Learn more</a></p>
        </div>
      </div>
    </div>
  </div>

  <div class="partners section" style="padding-bottom: 0">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-sm-9">
          <div class="row" style="align-items: center;">
            <div class="col-md-4">
              <img src="<?php echo get_template_directory_uri(); ?>/img/partners/gt-logo.png" width="130px" alt="Georgia Tech">
            </div>
            <div class="col-md-4">
              <img src="<?php echo get_template_directory_uri(); ?>/img/partners/cema-logo.png" width="160px" alt="Emergency Management Chatham County Logo">
            </div>
            <div class="col-md-4">
              <img src="<?php echo get_template_directory_uri(); ?>/img/partners/savannah-logo.png" width="190px" alt="City of Savannah Logo">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="about section">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-md-9">
          <p>Our goal is to provide real-time information about water levels across Chatham County to aid in emergency planning and response during episodes of flooding associated with storms, king tides, and other environmental events. The sea level data also provide a unique and important dataset to aid scientists, engineers, and regional planners in quantifying the short- and long-term risks associated with continued sea level rise.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="map section" id="locations" aria-label="Map of all the sea level sensors installed around Chatham County">
    <div class="container">
      <h2>We've installed <span id="numSensors"></span> sensors around Chatham County</h2>
      <div id='map'></div>
    </div>
  </div>

  <div class="cta section" id="get-involved">
    <div class="container">
      <h2>Get involved</h2>
      <div class="row">
        <div class="col-md-6">
          <h3>Community members</h3>
          <p>Interested in hosting a sea level sensor or gateway on your property? <a href="https://goo.gl/forms/SBV1q9mG2nlIjkqA3" target="_blank" aria-label="Request to host a sea level sensor or gateway on your property">Join the network</a>.</p>
        </div>
        <div class="col-md-6">
          <h3>Public officials</h3>
          <p>Want to learn more about how your community can benefit from sensor technology? <a target="_blank" href="mailto:globalchange@gatech.edu?subject=Inquiry about Sea Level Sensors" aria-label="Email address for public officials to contact about sensor technology opportunities">Contact us</a>.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <h3>Teachers</h3>
          <p>Interested in bringing sensor technology and data into your classroom? <a href="/education">Check out our curriculum</a>.</p>
        </div>
        <div class="col-md-6">
          <h3>Developers</h3>
          <p>Want to access data from the sensors? <a href="https://dev.sealevelsensors.org/" aria-label="Use our sea level sensors developer API website">Use our API</a> or view the sensor data live through the <a href="https://sealevelsensors.org/disclaimer/" aria-label="Live sensor data dashboard">dashboard.</a></p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <h3>Students</h3>
          <p>Curious about research opportunities or other ways to get involved? <a target="_blank" href="mailto:globalchange@gatech.edu?subject=Inquiry about Sea Level Sensors" aria-label="Email address for students to contact about ways to get involved with the project">Contact us</a>.</p>
        </div>
        <div class="col-md-6">
          <h3>Join our newsletter</h3>
          <p>Want to get the latest news and events from the Smart Sea Level Sensors project delivered to your inbox? <a target="_blank" href="http://eepurl.com/dFgs6X" aria-label="Sign up for Smart Sea Level Sensors newsletter">Sign up here</a>.</p>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
// initialization
mapboxgl.accessToken = 'pk.eyJ1IjoibHBvbGVwZWRkaSIsImEiOiJjazE1Z3c2MDkwdXY3M2h0Y2w2NWVuamlhIn0.fC-EAuhVfX3bTyEUEB0l8Q';

let map = new mapboxgl.Map({
  container: 'map', // container id
  style: 'mapbox://styles/mapbox/streets-v11', // stylesheet location
  center: [-81.1, 31.99], // starting position [lng, lat]
  zoom: 9.5 // starting zoom
});

// map.scrollZoom.disable();
 
map.on('load', function () { 
  let layer = {
    "id": "points",
    "type": "circle",
    "source": {
      "type": "geojson",
      "data": {
        "type": "FeatureCollection",
        "features": []
      }
    },
    "paint": {
      "circle-radius": 10,
      "circle-color": '#003058',
    }
  }

  fetch('https://api.sealevelsensors.org/v1.0/Things?$expand=Locations', {
    method: 'GET',
    cache: 'no-cache',
    headers: {
      'Content-Type': 'application/json'
    },
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      let sensors = data.value;

      document.getElementById("numSensors").innerHTML = sensors.length;

      for (let i = 0; i < sensors.length; i += 1) {
        let feature = {
          "type": "Feature",
          "geometry": {
            "type": "Point",
            "coordinates": sensors[i].Locations[0].location.coordinates,
          }
        }

        layer.source.data.features.push(feature);
      }

      map.addLayer(layer);
      return true;
    })
    .catch((err) => {
      console.log(err)
      return false;
    })
});

</script>

<?php get_footer(); ?>