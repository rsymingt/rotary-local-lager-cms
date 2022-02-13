<?php

  function remove_post($id, $title, $content, $date, $oldtitle, $folderTitle)
  {
    if($mysqli = connect_or_create_db())
    {
      unlink("../news/$folderTitle/index.php");
      rmdir("../news/$folderTitle");
      $sql = "DELETE FROM news_blog WHERE id=$id";
      if($mysqli->query($sql))
      {
        // echo "successfully updated post!";
      }
      $mysqli->close();
    }
  }

  function update_post($id, $title, $content, $oldtitle, $folderTitle)
  {
    if($mysqli = connect_or_create_db())
    {

      $dash_title = str_replace(" ", "-", $title); //CHECK IF ALREADY EXIST
      if(file_exists("../news/".$dash_title))
      {
        $i = 0;
        for(; file_exists("../news/".$dash_title."-$i"); $i+=1);
        $dash_title .= "-$i";
      }

      if($title === $oldtitle)
        $dash_title = $folderTitle;

      $sql = "UPDATE news_blog SET title='$title', folderTitle='$dash_title', content='$content' WHERE id=$id";
      if($mysqli->query($sql))
      {
        // echo "successfully updated post!";

        $old_title = str_replace(" ", "-", $oldtitle);
        unlink("../news/$old_title/index.php");
        rmdir("../news/$old_title");
        if(mkdir("../news/$dash_title"))
        {

          require_once("template.php");
          session_start();
          $contents = create_template($title." | Rotary Local Lager", '', $_SESSION['username'], $dash_title);

          $file = fopen("../news/$dash_title/index.php", "w");
          fwrite($file, $contents);
          fclose($file);
        }

        return $dash_title;
      }
      $mysqli->close();
    }
  }

  function load_post($folderTitle, $edit){
    if($mysqli = connect_or_create_db())
    {
      // $title = str_replace("-", " ", $title);
      $sql = "SELECT * FROM news_blog WHERE folderTitle='$folderTitle'";
      if($result = $mysqli->query($sql))
      {
        if($obj = $result->fetch_object())
        {
          $id = $obj->id;
          $title = $obj->title;
          $date = $obj->date;
          $content = $obj->content;
          $posted_by = $obj->posted_by;
          $folderTitle = $obj->folderTitle;

          // $datetime = new DateTime($date);
          // $newDate = $datetime->format("M") . " " . $datetime->format("d") . ", " . $datetime->format("Y");

          $datetime = strtotime( $date );
          $newDate = date( 'dS \of F Y', $datetime );

          echo "<section id='blog-section' class='section flex center-flex white-section no-edit' data-type='white-section' data-layout='center-flex' ".
                "data-body='regular-big'>\n";

          echo '<ol id="breadcrumb" class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/news">news</a></li>
                  <li class="breadcrumb-item active">'.$title.'</li>
                </ol>';

          echo "<span id='blog-wrapper' class='wrapper flex vertical-flex regular-big'  data-article_color='transparent' >";

          echo "<article id='post_$id' class=' ispost animation-element slideInUp $edit' data-article_color='transparent' style='background-color:transparent; transition-delay: 0;'>
              <div id='post_element_$id' class='content post ' data-edit='no' data-animation_delay='0' data-type='post' data-animation='slideInUp' >";

          if($edit)
          {
            echo "<form action='/php/post.php' method='post'>";

            echo '<div class="blog-entry">';

            echo '<input type="text" name="title" value="'.$title.'" class="blog-title post-link lager-orange">';

            echo "<time>$newDate</time>";

            echo "</div><hr>";

            echo "<textarea name='content' id='$title' class='blog-excerpt'>";

            echo "$content";

            echo "</textarea>";

            echo '<script>
                CKEDITOR.replace("content",{
                        language: "en",
                        uiColor: "#9AB8F3",
                        allowedContent: true,
                        height:"auto",
                        pasteFromWordRemoveStyles: false,
                        pasteFromWordRemoveFontStyles: false,
                        disallowedContent: "script; *[on*]",

                        filebrowserBrowseUrl: "/ckfinder/ckfinder.html",
                        filebrowserUploadUrl: "/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files"
                    });
            </script>';

            echo '<div style="display:flex; justify-content:space-between; width: 100%;">
                    <input type="hidden" name="id" value="'.$id.'">
                    <input type="hidden" name="oldtitle" value="'.$title.'">
                    <input type="hidden" name="folderTitle" value="'.$folderTitle.'">
                    <input type="submit" class="btn btn-primary" name="action" value="save">
                    <input type="submit" class="btn btn-primary" name="action" value="delete">
                  </div>';

            echo "</form>";
          }
          else
          {
            echo '<div class="blog-entry text-center">';

            echo '<h3 class="blog-title text-center post-link lager-orange">'.$title.'</h3>';



            echo "<time>$newDate</time>";

            echo "</div><hr>";

            echo "<div id='$title' class='blog-excerpt'>";

            echo "$content";

            echo "</div>";
          }

          echo "</div>";

          echo "</article>";

          echo "</span>\n</section>";
        }
      }
      $mysqli->close();
    }
  }

  function list_news($results){
    if($results)
    {
      echo "<section id='blog-section' class='section flex center-flex white-section no-edit' data-type='white-section' data-layout='center-flex' ".
            "data-body='regular-big'>\n";

      echo "<span id='blog-wrapper' class='wrapper flex vertical-flex regular-big'  data-article_color='transparent' >";

      $delay = 0;
      for($i = 0; $i < count($results->data); $i++)
      {
        $obj = $results->data[$i];
        $id = $obj->id;
        $title = $obj->title;
        $date = $obj->date;
        $content = $obj->content;
        $posted_by = $obj->posted_by;
        $folderTitle = $obj->folderTitle;

        if(strpos($folderTitle, 'no-title') !== false && !session_is_admin())
        {
          continue;
        }

        if(!(isset($_SESSION['loggedin']) && isset($_SESSION['clearance']) &&
            $_SESSION['clearance'] == 0))
        {
            if(strpos($folderTitle, "no-title") !== false)
            {
                continue;
            }
        }

        // echo $date;

        // $datetime = new DateTime($date);
        // $newDate = $datetime->format("M") . " " . $datetime->format("d") . ", " . $datetime->format("Y");

        $datetime = strtotime( $date );
        $newDate = date( 'dS \of F Y', $datetime );
        // $newDate = date( 'Y-m-d H:i:s', $datetime );

        $content = strip_tags($content);

        if(strlen($content) > 256)
          $content = substr($content, 0, 249)." [...] ";

        if(strlen($title) == 0)
          $title = 'notitle';

        echo "<article id='post_$id' class=' ispost animation-element slideInUp' data-article_color='transparent' style='background-color:transparent; transition-delay: ".$delay."ms;'>
            <div action='./' id='post_element_$id' data-edit='no' class='content post' data-animation_delay='$delay' data-type='post' data-animation='slideInUp' >";

        echo '<div class="blog-entry">';

        echo '<h3 class="blog-title"><a class="post-link lager-orange" href="'.$folderTitle.'" data-id="'.$id.'">'.$title.'</a>
              </h3>
            </div>';

        echo "<div class='blog-meta'>";

        echo "<time>$newDate</time>";
        // echo "<time>date</time>";

        echo "</div>";

        echo "<div class='blog-excerpt'>";

        echo "$content";

        echo "</div>";

        echo '<form hidden="true" id="post-form-'.$id.'" method="post" action="./">
               <input type="hidden" name="id" value="'.$id.'">
             </form>';

        echo "</div>";

        echo "</article>";

        $delay = $delay + 100;
      }
      echo "</span>\n</section>";
    }
  }

 ?>
