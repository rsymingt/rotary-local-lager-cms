<?php

  function create_post($loggedIn)
  {
    if($loggedIn){
      if($mysqli = connect_or_create_db())
      {
        $i = 0;
        for($i = 0; file_exists("no-title-" . $i); $i+=1);
        $title = "no title $i";
        if(!file_exists("no-title"))
          $title = "no title";
        $dash_title = str_replace(" ", "-", $title);
        $title = "no title";

        $values = "('$title', '$dash_title', NOW(), '', '".$_SESSION['username']."')";
        $sql = "INSERT INTO news_blog (title, folderTitle, date, content, posted_by) VALUES $values";
        echo $sql;
        if($mysqli->query($sql)){
          $id = $mysqli->insert_id;

          if(mkdir($dash_title))
          {
            require_once("template.php");
            $contents = create_template($title." | Rotary Local Lager", '', $_SESSION['username'], $dash_title);

            $file = fopen("$dash_title/index.php", "w");
            fwrite($file, $contents);
            fclose($file);

            return $dash_title;
          }
        }
        $mysqli->close();
      }
    }
  }

 ?>
