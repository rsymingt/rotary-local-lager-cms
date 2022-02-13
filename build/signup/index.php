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
  <title>Signup | Rotary Local Lager</title>
  <!-- <meta name="description" content="Newsletters for Rotary Local Lager"> -->
  <meta name="author" content="Ryan Symington">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <?php include("../html/css.php"); ?>


  <style>

    #form-content{
      padding: 10px;
      flex:1;
    }

    .form-row > *{

    }

    .form-row .form-group{
      flex:1;
    }

    .form-row{
      justify-content: center;
    }

    .form-group{
      padding-left: 5px;
      padding-right: 5px;
    }

    .form-section{
      padding: 25px;
      border: 1px solid black;
    }

  </style>

</head>

<body>

  <?php
    echo generateHeader('partnership_opportunities');
   ?>

   <div id="main-content">

   <?php
      require_once('../php/section.php');
      generate_sections("partnership_opportunities", session_is_admin());

      // Form stuff
    ?>

    <section class="section flex center-flex white-section">
      <span class="wrapper flex center-flex regular-big">
        <article class="isstretch-text animation-element slideInUp" data-article_color='transparent'>
          <div id="form-content" class="content stretch-text" data-edit="no">

            <form class="form-block" action="php/send_form" method="POST">

              <div class="form-section">

                <div class="row center-flex">
                  <h3>Beer Store Location Information</h3>
                </div>
                <hr>

                <div class="row form-row">
                  <div class="md-form form-group required">
                    <!-- <i class="fa fa-lock prefix"></i> -->
                    <input required="true" id="store-address" name="store-address" type="text" class="form-control validate">
                    <label for="store-address">Address</label>
                  </div>

                  <div class="md-form form-group required">
                    <input required="true" id="store-city" name="store-city" type="text" class="form-control validate">
                    <label for="store-city">City</label>
                  </div>
                </div>

                <div class="row form-row">
                  <div class="md-form form-group">
                    <!-- <i class="fa fa-lock prefix"></i> -->
                    <input id="store-num" name="store-num" type="text" class="form-control validate">
                    <label for="store-num">Store #</label>
                  </div>

                  <div class="md-form form-group">
                    <input id="store-phone" name="store-phone" type="text" class="form-control validate">
                    <label for="store-phone">Phone #</label>
                  </div>
                </div>
              </div>

              <div class="form-section">

                <div class="row center-flex">
                  <h3>Rotary Club Information</h3>
                </div>
                <hr>

                  <div class="row form-row">
                    <div class="md-form form-group required">
                      <input required="true" id="incorporated-name" name="incorporated-name" type="text" class="form-control validate">
                      <label for="incorporated-name">Incorporated Name</label>
                    </div>
                  </div>

                  <div class="row form-row">
                    <div class="md-form form-group required">
                      <input required="true" id="street-num" name="street-num" type="text" class="form-control validate">
                      <label for="street-num">Street Number</label>
                    </div>

                    <div class="md-form form-group required">
                      <input required="true" id="address" name="address" type="text" class="form-control validate">
                      <label for="address">Address</label>
                    </div>

                    <div class="md-form form-group required">
                      <input required="true" id="city" name="city" type="text" class="form-control validate">
                      <label for="city">City</label>
                    </div>

                    <div class="md-form form-group required">
                      <input required="true" id="post-code" name="post-code" type="text" class="form-control validate">
                      <label for="post-code">Postal Code</label>
                    </div>
                  </div>

                  <div class="row form-row">
                    <div class="md-form form-group required">
                      <input required="true" id="contact" name="contact" type="text" class="form-control validate">
                      <label for="contact">Contact Member</label>
                    </div>

                    <div class="md-form form-group required">
                      <input required="true" id="contact-email" name="contact-email" type="text" class="form-control validate">
                      <label for="contact-email">Email</label>
                    </div>

                    <div class="md-form form-group required">
                      <input required="true" id="contact-phone" name="contact-phone" type="text" class="form-control validate">
                      <label for="contact-phone">Phone #</label>
                    </div>
                  </div>

                  <div class="row form-row">
                    <div class="md-form form-group required">
                      <input required="true" id="treasurer" name="treasurer" type="text" class="form-control validate">
                      <label for="treasurer">Treasurer Contact</label>
                    </div>

                    <div class="md-form form-group required">
                      <input required="true" id="treasurer-email" name="treasurer-email" type="text" class="form-control validate">
                      <label for="treasurer-email">Email</label>
                    </div>

                    <div class="md-form form-group">
                      <input id="treasurer-phone" name="treasurer-phone" type="text" class="form-control validate">
                      <label for="treasurer-phone">Phone #</label>
                    </div>
                  </div>

                  <div class="row form-row">
                    <div class="md-form form-group required">
                      <input required="true" id="secretary" name="secretary" type="text" class="form-control validate">
                      <label for="secretary">Secretary Contact</label>
                    </div>

                    <div class="md-form form-group required">
                      <input required="true" id="secretary-email" name="secretary-email" type="text" class="form-control validate">
                      <label for="secretary-email">Email</label>
                    </div>

                    <div class="md-form form-group">
                      <input id="secretary-phone" name="secretary-phone" type="text" class="form-control validate">
                      <label for="secretary-phone">Phone #</label>
                    </div>
                  </div>

                  <div class="row form-row">
                    <div class="md-form form-group">
                      <input id="meeting-loc" name="meeting-loc" type="text" class="form-control validate">
                      <label for="meeting-loc">Meeting Location</label>
                    </div>

                    <div class="md-form form-group">
                      <input id="date" name="date" type="text" class="form-control validate">
                      <label for="date">Date</label>
                    </div>

                    <div class="md-form form-group">
                      <input id="time" name="time" type="text" class="form-control validate">
                      <label for="time">Time</label>
                    </div>
                  </div>

                  <div class="row form-row">
                    <div class="md-form form-group">
                      <input id="website" name="website" type="text" class="form-control validate">
                      <label for="website">Website</label>
                    </div>
                  </div>

              </div>

              <div id="button" class="md-form form-group">
                  <button onclick="confirmForm(this)" type="submit" class="btn btn-primary btn-lg z-depth-2">Submit</button>
              </div>

            </form>

          </div>
        </article>
      </span>
    </section>
  </div>

   <?php
     echo generateFooter();
   ?>

   <?php include("../html/scripts.php"); ?>


   <script type="text/javascript">
   var filename_noprefix = "partnership_opportunities"; //db name for file!

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
