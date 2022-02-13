<?php
  session_start();
  include("php/count.php");
  include('php/session.php');
  // $config['authentication'] = function() {
  //   return true;
  // };
  include('php/header-footer.php');
  // header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
  // header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  // header("Cache-Control: post-check=0, pre-check=0", false);
  // header("Pragma: no-cache");
  include("php/sql_handler.php");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Rotary Local Lager</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <!-- <meta name="description" content="Information on Rotary Local Lager"> -->
  <meta name="author" content="Ryan Symington">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Bootstrap core CSS -->
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="./css/mdb.min.css" rel="stylesheet">

  <link href="css/jquery-confirm.min.css" rel="stylesheet">

  <link href="jquery-ui-1.12.1\jquery-ui.min.css" rel="stylesheet">

  <link href="./css/stylesheet.css" rel="stylesheet">

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="js/rotary.js"></script>

  <script type="text/javascript" src="js/editor.js"></script>

  <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>

  <script src="jquery-ui-1.12.1\jquery-ui.min.js" type="text/javascript"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>

  <?php if(session_is_admin() || session_is_club()) : ?>
    <script src="../ckeditor/ckeditor.js"></script>
  <?php endif; ?>

  <script src="js/jquery-confirm.min.js" type="text/javascript"></script>

  <script src="js/parallax.js" type="text/javascript"></script>

  <style>

    #ll-banner{
      background-image: url("img/LL-banner.jpg");

      width: 100%;
      /* width: 100vw;
      height: 100vh; */
    }

    .parallax{
      perspective: 300px;
      /* height: 100vh; */
      /* overflow-x: hidden; */
      /* overflow-y: hidden; */
      /* Full height */
    }

    .parallax__group {
      position: relative;
      height: 100vh;
      transform-style: preserve-3d;
    }

    .parallax__layer {
      background-color: black;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }

    .parallax-deep{
      height:100vh;
      /* Create the parallax scrolling effect */
      /* background-attachment: fixed;
      background-position: right 72px;
      background-repeat: no-repeat;
      background-size: contain;
      background-size: 100%; */
      -webkit-transform: translateZ(-300px) scale(2);
      transform: translateZ(-300px) scale(2);
    }

    #parallax-section{
      /* position: relative;
        overflow:hidden; */
      /* height:500px; */
    }

    /* html{
      overflow-y: hidden;
    }

    body{
      overflow-y:scroll;
      height:100vh;
    } */

    .parallax-window {
      min-height: 30vw;
      background: transparent;
    }

    @media screen and (max-width: 1919px){
      .parallax-window {
        min-height: 25vh;
        background: transparent;
      }
    }

    #ll-banner{
      max-width: 100%;
      max-height: auto;
    }

  </style>

</head>

<body>

  <?php
  // substr($_SERVER['PHP_SELF'], 1, strpos($_SERVER['PHP_SELF'], '.') - 1)
    echo generateHeader('index');
   ?>

    <!-- <div class="parallax-window" data-parallax="scroll" data-image-src="img/LL-banner.jpg" data-speed="0.15" data-natural-width="1920" data-natural-height="635" data-position="right center"></div> -->

    <img src="img/LL-banner.jpg" id="ll-banner">

   <!-- <div id="parallax-section" class="parallax"> -->
   <!-- <section id="carousel-example-2" class="section transparent-section carousel slide carousel-fade size_to_viewport" data-ride="carousel"> -->
     <!-- <ol class="carousel-indicators">
       <li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
       <li data-target="#carousel-example-2" data-slide-to="1"></li>
       <li data-target="#carousel-example-2" data-slide-to="2"></li>
     </ol>
     <div class="carousel-inner text-center">
       <div class="carousel-item active transparent">
         <div style="width:fit-content; margin: auto; position:relative;">
           <img class="first-slide img-fluid" src="img/IMG_9408_low.jpg" data-img="img/IMG_9408.jpg" alt="...">
           <div class="carousel-caption d-none d-md-block">
             <h3>Rotary Local Lager</h3>
             <p>a win-win-win proposition</p>
           </div>
         </div>
       </div>
       <div class="carousel-item transparent">
         <div style="width:fit-content; margin: auto; position:relative;">
           <img class="second-slide img-fluid" src="img/IMG_9458_low.jpg" data-img="img/IMG_9458.jpg" alt="...">
           <div class="carousel-caption d-none d-md-block">
             <h3>Rotary Local Lager</h3>
             <p>a crisp refreshing blonde lager</p>
           </div>
         </div>
       </div>
       <div class="carousel-item transparent">
         <div style="width:fit-content; margin: auto; position:relative;">
           <img class="third-slide object-fit-contain" src="img/IMG_9440_low.jpg" data-img="img/IMG_9440.jpg" alt="...">
           <div class="carousel-caption d-none d-md-block">
             <h3>Rotary Local Lager</h3>
             <p>a significant fundraiser initiative for participating Rotary clubs supporting local projects</p>
           </div>
         </div>
       </div>
     </div>
     <div class="carousel-control-container">
       <a id="prev-btn" class="carousel-control-prev" href="#carousel-example-2" role="button" data-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="sr-only">Previous</span>
       </a>
     </div>
     <div class="carousel-control-container">
       <a id="next-btn" class="carousel-control-next" href="#carousel-example-2" role="button" data-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="sr-only">Next</span>
       </a>
     </div> -->
     <!-- <img id="ll-banner" class="img-fluid" src="img/LL-banner.jpg"> -->
     <!-- <div class="parallax__group">
       <div class="parallax__layer parallax-deep">
         <h1>TEST</h1>
       </div>
     </div>
    </div> -->

   <div id="main-content">


   <?php
      require_once('php/section.php');
      generate_sections("index", session_is_admin());
    ?>
  </div>

   <?php
     echo generateFooter();
   ?>



   <script type="text/javascript">
   var filename_noprefix = "index"; //db name for file!

   $window = $(window);
   $window.ready(function(){
     ready($window);

     session_is_admin(function(admin){
       if(admin)
         enable_admin_features();
     });
   });

   </script>

</body>

</html>
