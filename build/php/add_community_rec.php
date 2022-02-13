<?php
  include("sql_handler.php");

  if(isset($_POST['champion']) and isset($_POST['community']) and isset($_POST['address']))
  {
    $champion = $_POST['champion'];
    $community = $_POST['community'];
    $address = $_POST['address'];

    if(strlen($champion) == 0 or strlen($community) == 0 or strlen($address) == 0)
    {
      header("location: ../availability");
      die();
    }

    if($mysqli = connect_or_create_db())
    {
      $values = "('$champion', '$community', '$address')";
      $sql = "INSERT INTO availability_communities (champion, community, address) VALUES $values";
      if($mysqli->query($sql))
      {
        //SUCCESS
      }
      $mysqli->close();
    }


  }

  header("location: ../availability");
  die();
 ?>
