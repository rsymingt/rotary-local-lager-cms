<?php
  if(isset($_GET['community']))
  {
    session_start();
    include('../php/session.php');
    include("../php/sql_handler.php");
    include("../php/community.class.php");

    $Community = new CommunityListing();

    $community = $_GET['community'];

    if($community[0] === "participating")
    {
      if(!session_logged_in() || (!session_is_club() && !session_is_admin()))
      {
        header('location: ../login.php');
        die(0);
      }

      echo $Community->generateTable($community[1]);
    }
    else if($community[1] === "availability")
    {

    }
  }

?>
