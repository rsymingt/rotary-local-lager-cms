<?php
session_start();
include("../php/count.php");
  include('../php/session.php');
  if(!session_logged_in() || (!session_is_club() && !session_is_admin()))
  {
    header('location: ../login.php');
  }
  include('../php/header-footer.php');

  include("../php/sql_handler.php");
  include("../php/community.class.php");

  $Community = new CommunityListing();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Participating Clubs | Rotary Local Lager</title>
  <!-- <meta name="description" content="Information for Rotary Local Lager Participating Clubs"> -->
  <meta name="author" content="Ryan Symington">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <?php include("../html/css.php"); ?>


  <style>

    #main-content h1, #main-content h2, #main-content h3, #main-content h4, #main-content h5, #main-content h6{
      color:black !important;
      font-family: helvetica !important;
    }

    #main-content p{
      font-size: 1rem !important;
      color: black !important;
    }

  </style>

</head>

<body>

  <?php
    echo generateHeader('participating_clubs');
   ?>

   <div id="main-content">

   <?php
      // require_once("Thread.php");
      // if( ! Thread::isAvailable() ) {
      //   // $Community->generateHTML("community");
      // }
      // else{
      //   // function parallel(){
      //   //   $Community->generateHTML("community");
      //   // }
      //   //
      //   // $t = new Thread('parallel');
      //   // $t->start();
      // }

      $Community->generateHTML("community");

      require_once('../php/section.php');
      generate_sections("participating_clubs", session_is_admin());
    ?>
  </div>

   <?php
     echo generateFooter();
   ?>

   <?php include("../html/scripts.php"); ?>


   <script type="text/javascript">
   var filename_noprefix = "participating_clubs"; //db name for file!

   $window = $(window);
   $window.ready(function(){
     ready($window);

     session_is_admin(function(admin){
       if(admin)
         enable_admin_features();
        // else{
          // session_is_club(function(club){
          //   if(club)
          //     enable_admin_features();
          //
          // });
        // }
     });


     $(".dropdown-menu a").click(function(){
       var $this = $(this);
       var parent = $this.parent();

       parent.find(".active").removeClass("active");
       $this.addClass("active");
       $this.closest(".nav").find(".dropdown-toggle").text($this.text());

       // alert($this.text());

      $.ajax({
        type: 'GET',
        url: '../php/get.php',
        data: {
          community: ["participating", $this.text()]
        },
        success: function(data) {
          // alert(data);
          $("#participating_table").html(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          // alert(thrownError);
        }
      });
     });
   });

   </script>

</body>

</html>
