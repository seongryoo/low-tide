<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
  
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link 
        rel="stylesheet" 
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" 
        crossorigin="anonymous">
  
  <?php wp_head(); ?>
  
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  
  <header class="header" id="headerOpen">
    <div class="container overflow">

      <a 
         tabindex="0" 
         role="button" 
         aria-label="Open hamburger menu" 
         class="hamburger" id="hamburgerButton"
         aria-haspopup="menu"
         aria-controls="nav-menu"

         aria-pressed="false" 
         aria-expanded="false">
        <svg viewBox="0 0 400 400" 
             xmlns="http://www.w3.org/2000/svg" 
             alt="Hamburger menu icon" 
             aria-labelled-by="title desc"
             role="img">
          <title>Hamburger Menu Icon</title>
          <desc>Press enter or space to expand the primary menu.</desc>
          <g>
            <path class="bar-1" d="M40 40h320v80H40z" role="presentation"></path>
            <path class="bar-2" d="M40 160h320v80H40z" role="presentation"></path>
            <path class="bar-3" d="M40 280h320v80H40z" role="presentation"></path>
          </g>
        </svg>
      </a>

      <div class="logo">
        <a href="/" tabindex="0" class="logo-link"><img src="/img/logo.png" alt="Smart Sea Level Sensors Logo"></a>
      </div>

      <nav class="nav" id="navbar" aria-label="Primary menu" role="navigation">
        <ul role="menu" id="nav-menu">
          
          <?php
          if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu(
              array(
                'container' => '',
                'items_wrap' => '%3$s',
                'theme_location' => 'primary',
              )
            );
          }
          ?>
        </ul>
      </nav>

      <div class="dark-overlay" id="darkOverlay" aria-hidden="true"></div>

    </div>
  </header>