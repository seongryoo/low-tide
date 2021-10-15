<?php get_header(); ?>

<main id="content" class="<?php 
  if ( is_single() || is_page() ) {
    global $post;
    $post_slug = $post->post_name;
    echo $post_slug;
  }
?>">

<div class="section">
	<div class="container">
    <div class="row justify-content-md-center">
      <h1><?php the_title(); ?></h1>
      <div class="research-body">

        <table>
          <caption class="visually-hidden">Research-Grade Water Level Data Packages</caption>

          <thead>
            <tr>
              <th scope="col">Sensors</th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Timespan</th>
              <th scope="col">Download (size)</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <th scope="rowgroup" rowspan="2">All Sensors in Network</th>
              <th scope="row" class="data-pack-name">Raw Sensor Data <span class="sensor-num">(40 sensors)</span></th>
              <td>All sensors since the inception of the project; raw data streams</td>
              <td>
                May 2019 to present 
                <span class="footnoted">date 
                  <sup>
                    <a href="#fn1" id="r1">1</a>
                  </sup>
                </span>
              </td>
              <td class="downloads">
                <p>
                  <a href="">CSV</a> (0.6 <abbr title="gigabytes">GB</abbr>)
                </p>
                <p>
                  <a href="">JSON</a> (1.2 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">NetCDF</a> (2.4 <abbr>GB</abbr>)
                </p>
              </td>
            </tr>

            <tr>
              <th scope="row" class="data-pack-name">Quality Controlled Sensor Data <span class="sensor-num">(40 sensors)</span></th>
              <td>All sensors since the inception of the project; quality controlled and filtered data streams</td>
              <td>May 2019 to present</td>
              <td class="downloads">
                <p>
                  <a href="">CSV</a> (0.5 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">JSON</a> (1.0 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">NetCDF</a> (2.0 <abbr>GB</abbr>)
                </p>
              </td>
            </tr>

            <tr>
              <th scope="rowgroup" rowspan="5">Most Accurate &amp; Reliable Sensors</th>
              <th scope="row" class="data-pack-name">Most Reliable Sensors Subset <span class="sensor-num">(22 sensors)</span></th>
              <td>Sensors with the highest data return rates and with accurate elevation surveys</td>
              <td>One year ago to present date</td>
              <td class="downloads">
                <p>
                  <a href="">CSV</a> (0.4 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">JSON</a> (0.8 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">NetCDF</a> (1.6 <abbr>GB</abbr>)
                </p>
              </td>
            </tr>

            <tr>
              <th scope="row" class="data-pack-name">Coastal Subset <span class="sensor-num">(11 sensors)</span></th>
              <td>Subset of sensors that are deployed within 10 kilometers of the coastline</td>
              <td>One year ago to present date</td>
              <td class="downloads">
                <p>
                  <a href="">CSV</a> (0.2 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">JSON</a> (0.4 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">NetCDF</a> (0.8 <abbr>GB</abbr>)
                </p>
              </td>
            </tr>

            <tr>
              <th scope="row" class="data-pack-name">Intertidal Subset <span class="sensor-num">(11 sensors)</span></th>
              <td>Subset of sensors that are deployed beyond 10 kilometers of the ocean</td>
              <td>One year ago to present date</td>
              <td class="downloads">
                <p>
                  <a href="">CSV</a> (0.2 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">JSON</a> (0.4 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">NetCDF</a> (0.8 <abbr>GB</abbr>)
                </p>
              </td>
            </tr>

            <tr>
              <th scope="row" class="data-pack-name">Hurricane Dorian <span class="sensor-num">(22 sensors)</span></th>
              <td>Closer look at the effects of Dorian; peak anomalies occur at <time>9:00 PM (EST) on September 4, 2019</time></td>
              <td><time>August 25, 2019</time> to <time>September 14, 2019</time></td>
              <td class="downloads">
                <p>
                  <a href="">CSV</a> (0.1 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">JSON</a> (0.1 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">NetCDF</a> (0.2 <abbr>GB</abbr>)
                </p>
              </td>
            </tr>

            <tr>
              <th scope="row" class="data-pack-name">Fort Pulaski Comparison Subset <span class="sensor-num">(3 sensors)</span></th>
              <td>Data from two Georgia Tech sensors co-located with the <abbr title="National Oceanic and Atmospheric Administration">NOAA</abbr> Tide Gauge at Fort Pulaski</td>
              <td><time>June 2019</time> to present date</td>
              <td class="downloads">
                <p>
                  <a href="">CSV</a> (0.1 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">JSON</a> (0.1 <abbr>GB</abbr>)
                </p>
                <p>
                  <a href="">NetCDF</a> (0.2 <abbr>GB</abbr>)
                </p>
              </td>
            </tr>
          </tbody>
        </table>

      </div>

      <section class="footnotes">
        <h2 class="visually-hidden">Table footnotes</h2>

        <p id="fn1">
          <sup>
            <a href="#r1">1</a> 
          </sup>
          Earlier datasets available on request.
        </p>

      </section>
    </div>
  </div>
</div>