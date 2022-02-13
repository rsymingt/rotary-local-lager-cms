<?php
  function generateHeader($filename){
    if(session_is_club() || session_is_admin())
      $pages = array('', 'news', 'events', 'products', 'availability', 'participating_clubs');
    else
      $pages = array('', 'news', 'events', 'products', 'availability');

    $items = "";
    $fileheader = "";
    foreach($pages as $s)
    {
      $header = str_replace("_", " ", $s);
      $header = ucwords($header);

      if($s === $filename or ($s === '' and $filename === 'index'))
      {
        $items .= '<li class="nav-item active animation-element"> <a class="nav-link" href="/' . $s .'">';
        // $items .= '<li class="nav-item active"> <a class="nav-link" href="#mast-head">';
        if($header === "")
          $header = "Home";

        $fileheader = $header;
        $items .= $header . '</a></li>';
      }
      else {
        $items .= '<li class="nav-item animation-element"> <a class="nav-link" href="/' .$s  .'">';
        if($header === '')
          $header = "Home";
        // if($header === "./")
          // $header = ($filename === 'news') ? "News" : "Home";
        $items .= $header . '</a></li>';
      }
    }
    return <<<HTML
    <!-- <header id="mast-head" class="row p-0 m-0 elegant-color">
      <div id="logo" class="text-center col-md-3">
        <img  class="img-fluid" src="http://rsymingt.com/img/brand.png" >
      </div>
      <div id="brand" class="text-center col-md-12">
          <h1 class="m-0"><a class=" lager-orange" href="http://rotarylocallager.ca/index.php">Rotary Local Lager</a></h1>
      </div>
      <div class="offset-md-3"></div>
    </header> -->

      <nav id="main-nav" class="navbar navbar-expand-md fixed-top navbar-light bg-white">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <ul class="navbar-nav p-0">
            $items
          </ul>
        </div>
      </nav>
HTML;
  }
  // special-color-dark
  function generateFooter(){
    $logout_btn = (session_logged_in())?'<button id="logout_btn" type="button" class="btn btn-primary" onclick="logout()">Log Out ('.session_get_username().')</button>':'';
    return
    '<footer class="footer elegant-color">'.
      '<section class="section flex center-flex transparent-section" data-type="transparent-section" data-layout="center-flex" data-body="regular">
        <span class="wrapper flex center-flex regular-big" data-article_color="transparent">
          <article style="justify-content: flex-start;" class="istext animation-element pop in-view transition-done" data-article_color="transparent" style="background-color:transparent; transition-delay: 0ms;">
            <div class="content text" date-edit="no" data-animation_delay="0" data-type="calendar-container" data-animation="pop">'.
              '<h5 class="text-center">ROTARY LOCAL LAGER</h5><hr>'.
              '<a class="footer-link" href="/signup">Sign Up</a><br>'.
              '<a class="footer-link" href="/contact">Contact Us</a><br>'.
              '<a class="footer-link" href="/newsletters">Newsletters</a><br>'.
              '<a class="footer-link" href="/press_releases">Press Releases</a><br>'.
              '<a class="footer-link" href="/resources">Resources</a>'.
            '</div>
          </article>
        </span>
      </section>'.$logout_btn.
    '</footer>';
  }
?>
