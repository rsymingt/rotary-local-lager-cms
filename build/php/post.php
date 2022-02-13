<?php
  include("blog.php");
  include("sql_handler.php");

  if(isset($_POST['id']))
  {
    if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['action']) and isset($_POST['oldtitle']) and isset($_POST['folderTitle']))
    {
      if($_POST['action'] === "save")
        update_post($_POST['id'], $_POST['title'], $_POST['content'], $_POST['oldtitle'], $_POST['folderTitle']);
      else if($_POST['action'] === "delete")
        remove_post($_POST['id'], $_POST['title'], $_POST['content'], $_POST['date'], $_POST['oldtitle'], $_POST['folderTitle']);

    }
  }
  header("location: ../news");
  die();
 ?>
