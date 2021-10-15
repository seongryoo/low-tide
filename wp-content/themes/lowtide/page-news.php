<?php get_header(); ?>

<main id="content">

<div id="news" class="news section">
  <div class="container">
    <h1>In the news</h1>
    <div class="row">
      <?php render_news_cards(); ?>
    </div>
  </div>
</div>

<div id="project-updates" class="news section">
  <div class="container">
    <h2>Project updates</h2>
    <div class="row">
      
      <div class="col-md-4">
        <div class="card">
          <a href="/interns/">
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/team/interns-featured-image.png" alt="Kaylyn Sinisgalli, Summer 2020 Smart Sea Level Sensors Intern">
            <div class="card-body">
              <h4 class="card-title">Meet Our 2020 Summer Intern Team</h4>
            </div>
          </a>
        </div>
      </div>
      
      
      <div class="col-md-4">
        <div class="card">
          
          <a href="/news/march-2019-meeting-recap/">
          
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/events/mar-2019-meeting/mar-meeting.jpg" alt="March 2019 Meeting Recap">
            <div class="card-body">
              <h4 class="card-title">March 2019 Meeting Recap</h4>
            </div>
          </a>     
        </div> 
      </div>
      
      <div class="col-md-4">
        <div class="card">
          
          <a href="/news/january-2019-meeting-recap/">
          
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/events/jan-2019-meeting/jan-meeting-2.jpg" alt="January 2019 Meeting Recap">
            <div class="card-body">
              <h4 class="card-title">January 2019 Meeting Recap</h4>
            </div>
          </a>     
        </div> 
      </div>
      
      <div class="col-md-4">
        <div class="card">
          
          <a href="/news/coastal-flooding-solutions-in-savannah-and-beyond/">
          
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/events/coastal-flooding-solutions-in-savannah-and-beyond.png" alt="Kim Cobb and Russ Clark: "Coastal Flooding Solutions in Savannah and Beyond"">
            <div class="card-body">
              <h4 class="card-title">Kim Cobb and Russ Clark: "Coastal Flooding Solutions in Savannah and Beyond"</h4>
            </div>
          </a>     
        </div> 
      </div>
      
      <div class="col-md-4">
        <div class="card">
          
          <a href="/news/october-2018-meeting-recap/">
          
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/events/oct-2018-meeting/oct-meeting.jpg" alt="October 2018 Meeting Recap">
            <div class="card-body">
              <h4 class="card-title">October 2018 Meeting Recap</h4>
            </div>
          </a>     
        </div> 
      </div>
      
      <div class="col-md-4">
        <div class="card">
          
          <a href="/news/august-2018-meeting-recap/">
          
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/events/aug-2018-meeting/aug-meeting.jpg" alt="August 2018 Meeting Recap">
            <div class="card-body">
              <h4 class="card-title">August 2018 Meeting Recap</h4>
            </div>
          </a>     
        </div> 
      </div>
      
      <div class="col-md-4">
        <div class="card">
          
          <a href="http://www.news.gatech.edu/2018/06/11/four-communities-selected-inaugural-georgia-smart-communities-challenge" target="_blank">
          
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/news/ga-smart-communities-challenge.png" alt="Four Communities Selected for Inaugural Georgia Smart Communities Challenge">
            <div class="card-body">
              <h4 class="card-title">Four Communities Selected for Inaugural Georgia Smart Communities Challenge</h4>
            </div>
          </a>     
        </div> 
      </div>
      
      <div class="col-md-4">
        <div class="card">
          
          <a href="/news/may-2018-meeting-recap/">
          
            <img class="card-img" src="<?php echo get_template_directory_uri(); ?>/img/events/may-2018-meeting/may-meeting.jpg" alt="May 2018 Meeting Recap">
            <div class="card-body">
              <h4 class="card-title">May 2018 Meeting Recap</h4>
            </div>
          </a>     
        </div> 
      </div>
      
    </div>
  </div>
</div>
  
  </main>

<?php get_footer(); ?>