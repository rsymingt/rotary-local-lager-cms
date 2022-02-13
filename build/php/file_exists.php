<?php

  if(isset($_POST['file']))
  {
    if(file_exists($_POST['file']))
    {
      echo "true";
    }
    else {
      echo "false";
    }
  }

?>
