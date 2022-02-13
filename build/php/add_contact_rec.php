<?php
  include("sql_handler.php");

  if(isset($_POST['participating_club']) and isset($_POST['community']) and isset($_POST['club_contact']) and
      isset($_POST['title']) and isset($_POST['phone']) and isset($_POST['contact_email']) and
      isset($_POST['club_address'])){

    $data = array($_POST['participating_club'], $_POST['community'], $_POST['club_contact'], $_POST['title'],
                    $_POST['phone'], $_POST['contact_email'], $_POST['club_address']);

    $values = "(";
    $first = true;
    foreach($data as $col)
    {
      if(!$first)
        $values .= ",";
      $values .= "'$col'";
      $first = false;
    }
    $values .= ")";

    if($mysqli = connect_or_create_db())
    {
      $sql = "INSERT INTO contact_communities (participating_club, community, club_contact".
              ",title, phone, contact_email, club_address) VALUES $values";
      if($mysqli->query($sql))
      {
        //SUCCESS
      }
      $mysqli->close();
    }
  }

  header("location: ../contact");
  die();

 ?>
