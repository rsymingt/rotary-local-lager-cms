<?php
session_start();
include("../php/count.php");
  include('../php/session.php');
  include('../php/header-footer.php');
  include("../php/sql_handler.php");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Press Releases | Rotary Local Lager</title>
  <!-- <meta name="description" content="Press Releases for Rotary Local Lager"> -->
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
    echo generateHeader('resources');
   ?>

   <div id="main-content">

   <?php
      require_once('../php/section.php');
      generate_sections("resources", session_is_admin());
    ?>
  </div>

   <?php
     echo generateFooter();
   ?>

   <?php include("../html/scripts.php"); ?>

   <script type="text/javascript">
   var filename_noprefix = "resources"; //db name for file!

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
