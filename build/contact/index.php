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
  <title>Contact | Rotary Local Lager</title>
  <!-- <meta name="description" content="Contact page for Rotary Local Lager"> -->
  <meta name="author" content="Ryan Symington">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <?php include("../html/css.php"); ?>

  <style>

  #contactMaps{
    display:flex;
    align-items: center;
    flex-direction: row;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-left: auto;
    margin-right: auto;
  }

  #contactUs{
    flex:1;
    padding: 25px;
    /* min-width: 687px; */
    max-width: 687px;
    min-height: 687px;
  }

  #googleMap{
    position: relative;
    flex:1;
    /* min-width: 687px; */
    max-width: 687px;
    min-height: 687px;
    width:100%;
    height:100%;
    /* width:100%; */
  }

  #googleMap iframe{
    position: absolute;
    top:0;
    right:0;
    left:0;
    bottom:0;
  }

  @media screen and (max-width: 1400px)
  {
    #contactMaps{
      flex-direction: column;
      width: fit-content;
    }
  }

  @media screen and (max-width: 687px)
  {
    #googleMap{
      max-width: 100%;
      min-height: 400px;
      max-width: 400px;
    }
  }

  .contact-icons{
    padding: 0;
  }

  </style>

</head>

<body>

  <?php
    if(isset($_SESSION['email-status']))
    {
      echo "<script>alert('".$_SESSION['email-status']."');</script>";
      unset($_SESSION['email-status']);
    }
  ?>

  <?php
    echo generateHeader('contact');
   ?>

    <div id="main-content">
     <?php
        require_once('../php/section.php');
        generate_sections("contact", session_is_admin());
      ?>
      <span id="contactMaps" class="regular">
        <div id="contactUs">
          <!--Section heading-->
          <h2 style="color: #000099;" class="section-heading h1 pt-4 text-center">Contact us</h2>
          <!--Section description-->
          <!-- <div> -->
          <p style="color: #000099;" class="section-description text-center p-4">Do you have any questions? Please do not hesitate to contact us directly. Our team will come back to you within matter of hours to help you.</p>

          <div class="row">
              <!--Grid column-->
              <div class="col-md-8 col-xl-8">
                <form id ="contact-form" class="needs-validation" name="contact-form" action="/php/mail.php" method="POST">
                  <div class="form-row">
                    <div class="col-md-6 mb-3">
                      <div class="md-form">
                        <div class="form-group">
                          <input type="text" class="form-control" id="validationDefault01" name="name" required>
                          <label for="validationDefault01">First name</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-3">
                      <div class="md-form">
                        <div class="form-group">
                          <label for="validationDefault02">Your Email</label>
                          <input type="email" class="form-control" id="validationDefault02" name="email" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-12">
                      <div class="md-form">
                        <div class="form-group">
                          <label for="subject">Subject</label>
                          <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-12">
                      <div class="md-form">
                        <div class="form-group">
                          <label for="message">Your Message</label>
                          <textarea type="text" class="form-control md-textarea" id="message" name="message" required></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <button class="btn btn-primary" type="submit">Submit form</button>
                </form>
                  <div class="status" id="status"></div>
              </div>
              <!--Grid column-->

              <!--Grid column-->
              <div class="col-md-4 col-xl-4 text-center p-0">
                  <ul class="contact-icons" style="list-style:none;">
                      <li>
                        <a target="_blank" href="https://goo.gl/maps/QuqJxozewQw"><i class="fa fa-map-marker fa-2x"></i></a>
                      </li>
                      <br>
                      <li>
                        <a href="tel:519-823-3846"><i class="fa fa-phone fa-2x"></i></a>
                      </li>
                      <br>
                      <li>
                        <a href="mailto:news@rotarylocallager.ca"><i class="fa fa-envelope fa-2x"></i></a>
                      </li>
                  </ul>
              </div>
              <!--Grid column-->
          </div>
        </div>
        <div id="googleMap">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2893.68948998636!2d-80.19693378425947!3d43.50881276977303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b84de050b5f57%3A0x5e8fbbd34617125b!2sRotary+Clubs+Of+Guelph!5e0!3m2!1sen!2sca!4v1525750525103" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>

    </div>

   <?php
     echo generateFooter();
   ?>

   <?php include("../html/scripts.php"); ?>


   <script type="text/javascript">
   var filename_noprefix = "contact";

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
