<?php
session_start();
include("../php/count.php");
  include('../php/session.php');
  include('../php/header-footer.php');
  include('../php/sql_handler.php');
?>

<!DOCTYPE html>
<html>

<head>
  <title>Availability | Rotary Local Lager</title>
  <!-- <meta name="description" content="Rotary Local Lager Availability"> -->
  <meta name="author" content="Ryan Symington">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <?php include("../html/css.php"); ?>

  <style>

  </style>

</head>

<body>

  <?php
    echo generateHeader('availability');
   ?>

   <div id="main-content">

   <?php
      require_once('../php/section.php');
      generate_sections("availability", session_is_admin());
    ?>



    <?php
      require_once("../php/communities.php");
      generate_communities(session_is_admin() || session_is_club(), "availability");
     ?>

  </div>

   <?php
     echo generateFooter();
   ?>

   <?php include("../html/scripts.php"); ?>

   <script type="text/javascript">
     var filename_noprefix = "availability"; //db name for file!

     $window = $(window);
     $window.ready(function(){
       ready($window);

       session_is_admin(function(admin){
         if(admin)
           enable_admin_features();
       });

       $(".dropdown-menu a").click(function(){
         var $this = $(this);
         var parent = $this.parent();

         parent.find(".active").removeClass("active");
         $this.closest(".nav").find(".dropdown-toggle").text($this.text());
       });
     });

   </script>

</body>

</html>
