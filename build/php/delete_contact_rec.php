<?php
  include("sql_handler.php");

  if(isset($_POST['id']) and isset($_POST['page']))
  {
    $id = $_POST['id'];
    if($mysqli = connect_or_create_db())
    {
      $sql = "DELETE FROM contact_communities WHERE id='$id'";
      if($mysqli->query($sql)){

      }
      $mysqli->close();
    }


  }

  header("location: ../contact");
  die();
 ?>
