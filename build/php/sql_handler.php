<?php

  //$db IS TABLE INSTEAD

  function connect_or_create_db()
  {
    $user = "REDACTED";
    $pass = "REDACTED";
    $host = "REDACTED";
    $db = "main_rotarylocallager_ca";

    // $mysqli = mysqli_connect($host, $user, $pass);
    $mysqli = new mysqli($host, $user, $pass, $db);
    if($mysqli->connect_errno)
    {
      die('Could not connect: ' . $mysqli->connect_error);
    }
    return $mysqli;
  }

  // $table is pointless it will be $db_sections
  function move_section($db, $table, $section_id, $up)
  {
    if($mysqli = connect_or_create_db())
    {
      $sql = "SELECT * FROM ".$db."_sections ORDER BY id";
      if($result = $mysqli->query($sql))
      {
        $array = array();
        while($obj = $result->fetch_object())
        {
          $array[] = $obj;
        }
        for($i = 0; $i < sizeof($array); $i++)
        {
          if($array[$i]->element_id === $section_id)
          {
            $id = $array[$i]->id;
            $element_id = $array[$i]->element_id;
            $type = $array[$i]->type;
            $layout = $array[$i]->layout;
            $body = $array[$i]->body;
            $article_color = $array[$i]->article_color;
            if($up)
            {
              //move up
              if ($i > 0) //if theres space
              {
                $toSwap_id = $array[$i - 1]->id;
                $toSwap_element_id = $array[$i - 1]->element_id;
                $toSwap_type = $array[$i - 1]->type;
                $toSwap_layout = $array[$i - 1]->layout;
                $toSwap_body = $array[$i - 1]->body;
                $toSwap_article_color = $array[$i - 1]->article_color;
              }
            }
            else {
              // move down
              if($i + 1 !== sizeof($array)) //if there is space below
              {
                $toSwap_id = $array[$i + 1]->id;
                $toSwap_element_id = $array[$i + 1]->element_id;
                $toSwap_type = $array[$i + 1]->type;
                $toSwap_layout = $array[$i + 1]->layout;
                $toSwap_body = $array[$i + 1]->body;
                $toSwap_article_color = $array[$i + 1]->article_color;
              }
            }
            if(isset($toSwap_id))
            {
              $sql = "DELETE FROM ".$db."_sections WHERE id=$id";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "dropped";
              }

              $sql = "DELETE FROM ".$db."_sections WHERE id=$toSwap_id";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "dropped";
              }

              $sql = "INSERT INTO ".$db."_sections (id, element_id, type, layout, body, article_color) VALUES
              ($toSwap_id, '$element_id', '$type', '$layout', '$body', '$article_color')";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "updated toswap";
              }

              $sql = "INSERT INTO ".$db."_sections (id, element_id, type, layout, body, article_color) VALUES
              ($id, '$toSwap_element_id', '$toSwap_type', '$toSwap_layout', '$toSwap_body', '$toSwap_article_color')";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "updated";
              }
            }
          }
        }
      }
      $mysqli->close();
    }
  }

  function update_section($db, $element_id, $type, $layout, $body, $article_color, $section_above)
  {
    if($mysqli = connect_or_create_db())
    {
      $sql = "SELECT * FROM ".$db."_sections WHERE element_id='$element_id'";
      if($result = $mysqli->query($sql))
      {
        if(mysqli_num_rows($result) > 0)
        {
          //UPDATE
          $sql = "UPDATE ".$db."_sections SET type='$type', layout='$layout', body='$body', article_color='$article_color' WHERE element_id='$element_id'";
          if($result = $mysqli->query($sql))
          {
            // UPDATED
            echo "UPDATED $element_id";
          }
          return;
        }
      }
      else {
        // sections table doesnt exist
        // NO SECTIONS TABLE
        $sql_create_table = "CREATE TABLE ".$db."_sections (
          id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          element_id VARCHAR(64) NOT NULL,
          type VARCHAR(64),
          layout VARCHAR(64),
          body VARCHAR(64),
          article_color VARCHAR(64)
        )";
        if($mysqli->query($sql_create_table))
        {
        }
      }
      echo "TEST";

      $sql = "SELECT id FROM ".$db."_sections WHERE element_id='$section_above'";
      $section_above_id = 0;
      if($result = $mysqli->query($sql))
      {
        // echo "TEST";
        while($obj = $result->fetch_object()){
          // echo 'username: ' . $row["AES_DECRYPT(password, 'cheese12')"] . '<br>';
          $section_above_id = $obj->id;
        }

        $sql = "UPDATE ".$db."_sections SET id = id + 1 WHERE id > $section_above_id order by id DESC";

        if($result = $mysqli->query($sql))
        {
          echo "ids shifted";
        }
        $section_above_id = $section_above_id + 1;

        $sql = "INSERT INTO ".$db."_sections (id, element_id, type, layout, body, article_color) VALUES ($section_above_id,'$element_id', '$type', '$layout', '$body', '$article_color')";
        if($result = $mysqli->query($sql))
        {
          echo "section created";
        }
        return;
        // echo $result;
      }

      $sql = "INSERT INTO ".$db."_sections (element_id, type, layout, body, article_color) VALUES ('$element_id', '$type', '$layout', '$body', '$article_color')";
      if($result = $mysqli->query($sql))
      {
        echo "section created";
      }

      // $sql = "CREATE TABLE $element_id (
      //   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      //   element_id VARCHAR(64) NOT NULL,
      //   type VARCHAR(64),
      //   animation VARCHAR(64),
      //   data TEXT(60000),
      //   animation_delay INT(64)
      // )";
      // if($mysqli->query($sql))
      // {
      // }
      $mysqli->close();
    }
  }

  function move_item($db, $section, $element_id, $right)
  {
    if($mysqli = connect_or_create_db())
    {
      $sql = "SELECT * FROM ".$db."_sections_elements WHERE section='$section'";
      if($result = $mysqli->query($sql))
      {
        $array = array();
        while($obj = $result->fetch_object())
        {
          $array[] = $obj;
        }
        for($i = 0; $i < sizeof($array); $i++)
        {
          if($array[$i]->element_id === $element_id)
          {
            $id = $array[$i]->id;
            $section = $array[$i]->section;
            $element_id = $array[$i]->element_id;
            $type = $array[$i]->type;
            $animation = $array[$i]->animation;
            $data = $array[$i]->data;
            if(!$right)
            {
              //move up
              if ($i > 0) //if theres space
              {
                $toSwap_id = $array[$i - 1]->id;
                $toSwap_section = $array[$i - 1]->section;
                $toSwap_element_id = $array[$i - 1]->element_id;
                $toSwap_type = $array[$i - 1]->type;
                $toSwap_animation = $array[$i - 1]->animation;
                $toSwap_data = $array[$i - 1]->data;
              }
            }
            else {
              // move down
              if($i + 1 !== sizeof($array)) //if there is space below
              {
                $toSwap_id = $array[$i + 1]->id;
                $toSwap_section = $array[$i + 1]->section;
                $toSwap_element_id = $array[$i + 1]->element_id;
                $toSwap_type = $array[$i + 1]->type;
                $toSwap_animation = $array[$i + 1]->animation;
                $toSwap_data = $array[$i + 1]->data;
              }
            }
            if(isset($toSwap_id))
            {
              $sql = "DELETE FROM ".$db."_sections_elements WHERE id=$id";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "droped";
              }

              $sql = "DELETE FROM ".$db."_sections_elements WHERE id=$toSwap_id";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "dropped";
              }

              $sql = "INSERT INTO ".$db."_sections_elements (id,section, element_id, type, animation, data) VALUES
              ($toSwap_id, '$section', '$element_id', '$type', '$animation', '$data')";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "updated toswap";
              }

              $sql = "INSERT INTO ".$db."_sections_elements (id,section, element_id, type, animation, data) VALUES
              ($id,'$toSwap_section', '$toSwap_element_id', '$toSwap_type', '$toSwap_animation', '$toSwap_data')";
              if($mysqli->query($sql))
              {
                //successfully updated
                echo "updated";
              }
            }
          }
        }
      }
      $mysqli->close();
    }
  }

  function update_element($db, $section, $element_id, $type, $animation, $data, $animation_delay)
  {
    if($mysqli = connect_or_create_db()){
      $sql = "SELECT * FROM ".$db."_sections_elements WHERE element_id='$element_id'";
      if($result = $mysqli->query($sql))
      {
        if(mysqli_num_rows($result) > 0)
        {
          $sql = "UPDATE ".$db."_sections_elements SET type='$type', animation='$animation', data='$data', animation_delay='$animation_delay' WHERE element_id='$element_id'";
          //update element with new data
          if($newResult = $mysqli->query($sql))
          {
            //query successful..
            echo "ELEMENT UPDATED";
          }
        }
        else {
          $sql = "INSERT INTO ".$db."_sections_elements (element_id, type, animation, data, animation_delay) VALUES ('$element_id', '$type', '$animation', '$data', '$animation_delay')";

          // create new element... SHOULDNT BE NEEDED.....
          if($newResult = $mysqli->query($sql))
          {
            //query successful..
            echo "ELEMENT CREATED!";
          }
        }
      }
      $mysqli->close();
    }
  }

  function add_element($db, $section, $element_id, $type, $animation, $animation_delay)
  {
    if($mysqli = connect_or_create_db())
    {
      $sql = "CREATE TABLE IF NOT EXISTS ".$db."_sections_elements (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        section VARCHAR(64),
        element_id VARCHAR(64) NOT NULL,
        type VARCHAR(64),
        animation VARCHAR(64),
        data MEDIUMTEXT,
        animation_delay INT(64),
        color VARCHAR(64)
      )";
      if($mysqli->query($sql))
      {
      }

      echo $db;
      $sql = "INSERT INTO ".$db."_sections_elements (section, element_id, type, animation, data, animation_delay) VALUES
      ('$section','$element_id', '$type', '$animation', '', '$animation_delay')";
      if($result = $mysqli->query($sql))
      {
      }
      $mysqli->close();
    }
  }

  function remove_element($db, $section, $element_id)
  {
    if($mysqli = connect_or_create_db())
    {
      $sql = "DELETE FROM ".$db."_sections_elements WHERE element_id='$element_id'";
      if($result = $mysqli->query($sql))
      {
        // echo "YAY";
      }
      $mysqli->close();
    }
  }

  function remove_section($db, $element_id)
  {
    if($mysqli = connect_or_create_db())
    {
      $sql = "DELETE FROM ".$db."_sections WHERE element_id='$element_id'";

      if($result = $mysqli->query($sql))
      {
        $sql = "DELETE FROM ".$db."_sections_elements WHERE section='$element_id'";

        if($mysqli->query($sql))
        {
          echo "elements successfully removed";
        }
      }
      $mysqli->close();
    }
  }
 ?>
