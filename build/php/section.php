<?php
  function generate_sections($table, $gen){
    // require_once('session.php');
    // require_once('php/sql_handler.php');
    $mysqli = connect_or_create_db("rsymingt_pages");

    if($mysqli){
      $sql = "SELECT * FROM ".$table."_sections ORDER BY id";

      // query for sections in file
      $section_count = 0;
      if($sections = $mysqli->query($sql)){

        // loop through each section object
        while($sections_obj = $sections->fetch_object())
        {
          $section_count = $section_count + 1;
          // get info for section
          $section_id = $sections_obj->element_id;
          $section_type = $sections_obj->type;
          $section_layout = $sections_obj->layout;
          $section_body = $sections_obj->body;
          $article_color = $sections_obj->article_color;
          $article_id = $section_id . "_article";

          //construct section tag to surround elements
          $edit_mode = "";
          if($gen){
            $edit_mode = "edit-mode";
          }
          echo "<section id='$section_id' class='section flex $section_layout $section_type $edit_mode' data-type='$section_type' data-layout='$section_layout' ".
                "data-body='$section_body'>\n";
          echo "<span class='wrapper flex $section_layout $section_body'  data-article_color='$article_color' >";

          // query the section_id database for elements
          $sql = "SELECT * FROM ".$table."_sections_elements WHERE section='$section_id'";
          if($section = $mysqli->query($sql))
          {
            $imgs = array();
            $captions = array();
            $num_items = 0;

            $count = 0;
            while($section_obj = $section->fetch_object())
            {
              $count = $count + 1;
              // get info for section element i
              $element_id = $section_obj->element_id;
              $element_type = $section_obj->type;
              $element_animation = $section_obj->animation;
              $element_data = $section_obj->data;
              $element_animation_delay = $section_obj->animation_delay;
              $animation_delay = $element_animation_delay."ms";

              if($element_type === 'carousel_obj')
              {
                $rec = 0;
                $string = "";
                $img = array();
                $text = "";
                $caption = "";

                //set up carousel

                for($i = 0; $i < strlen($element_data); $i++)
                {
                  $dc = $element_data[$i];

                  if($rec)
                  {
                    $text .= $dc;
                    if($dc === '>')
                    {
                      $num_items++;
                      $rec = 0;
                      //image tag
                      //create carousel item
                      $img[] = $text;
                      $text = "";
                    }
                  }
                  else {
                    $caption .= $dc;
                  }


                  if(ord($dc) >= 97 and ord($dc) <= 122)
                  {
                    $string .= $dc;
                  }
                  else if(strlen($string) > 0)
                  {
                    // echo $string;
                    if($string === 'img' and !$rec)
                    {
                      $rec = 1;
                      //original $i
                      $oi = $i;
                      for(;$i > 0 and $element_data[$i] != '<'; $i--);
                      $i--;

                      $caption = substr($caption, 0, strlen($caption) - ($oi - $i));

                      continue;
                    }
                    $string = "";
                  }
                }

                echo "<article class='is$element_type'>";

                echo '<div id="carousel-example-2" class="carousel_obj carousel slide carousel-fade" data-ride="carousel">';

                echo '<ol class="carousel-indicators">';
                for($i = 0; $i < sizeof($img); $i++)
                {
                  if($i === 0){
                    echo '<li data-target="#carousel-example-2" data-slide-to="'.$i.'" class="active"></li>';
                  }
                  else
                  {
                    echo '<li data-target="#carousel-example-2" data-slide-to="'.$i.'"></li>';
                  }
                }
                echo '</ol>';

                echo '<div class="carousel-inner text-center">';
                $captions = explode("<hr>", $caption);
                for($i = 0; $i < sizeof($img) and $i < 3; $i++)
                {
                  if($i === 0)
                    echo '<div class="carousel-item active transparent">';
                  else
                    echo '<div class="carousel-item transparent">';

                  //add image
                  switch($i){
                    case 0:
                      $img[$i] = substr($img[$i], 0, strlen($img[$i]) - 1) . 'class="animation-element first-slide object-fit-contain"' . '>';
                      break;
                    case 1:
                      $img[$i] = substr($img[$i], 0, strlen($img[$i]) - 1) . 'class="second-slide object-fit-contain"' . '>';
                      break;
                    case 2:
                      $img[$i] = substr($img[$i], 0, strlen($img[$i]) - 1) . 'class="third-slide object-fit-contain"' . '>';
                      break;
                  }

                  echo $img[$i];
                  if($i < sizeof($captions))
                  {
                    echo '<div class="carousel-caption d-none d-md-block">';
                    //add caption
                    echo $captions[$i];
                    echo '</div>';
                  }
                  echo '</div>';
                }
                echo '</div>';
                echo '<div class="carousel-control-container">
                  <a id="prev-btn" class="carousel-control-prev" href="#carousel-example-2" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                </div>
                <div class="carousel-control-container">
                  <a id="next-btn" class="carousel-control-next" href="#carousel-example-2" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
              </div>';

              echo "</article>";

                //deconstruct carousel
              }
              // else {
                $element_div = "<div id='$element_id' class='content $element_type' data-animation_delay='$element_animation_delay' data-type='$element_type' data-animation='$element_animation' >";
                echo
                "<article id='$article_id"."_"."$count' class=' is$element_type animation-element $element_animation $edit_mode' data-article_color='$article_color' style='background-color:$article_color; transition-delay: $animation_delay;'>
                  $element_div
                    $element_data
                  </div>";
                  if($gen)
                  echo "<div class=\"section-bar\">
                    <button type=\"button\" class=\"close btn-remove-section\" onclick=\"removeElement($element_id)\"><i class=\"fa fa-remove\"></i></button>
                    <button type=\"button\" class=\"btn btn-default btn-section-properties\" onclick=\"updateElement($element_id)\">Properties (item)</button>
                    <div class=\"section-order\">
                      <button type='button' class='btn btn-default' onclick='move_item($element_id, 0)'><i class=\"fa fa-arrow-left\"></i></button>
                      <button type='button' class='btn btn-default' onclick='move_item($element_id, 1)'><i class=\"fa fa-arrow-right\"></i></button>
                    </div>
                  </div>";
                echo "</article>";
              // }
            }
          }
          echo "</span>";

          if($gen)
          {
            echo <<<HTML
             <button type="button" class="btn btn-default btn-add-element" onclick="addElement(this)">+</button>
             <div class="section-bar" style="">
               <button type="button" class="btn btn-default btn-remove-section" onclick="removeSection(this)">
                  <i class="fa fa-remove"></i>
                </button>
                <button type="button" class="btn btn-default btn-section-properties" onclick="updateSection(this)">Properties (section)</button>
                <div class="section-order">
                  <button type='button' class='btn btn-default' onclick='move_section(this, 1)'><i class="fa fa-arrow-up"></i></button>
                  <button type='button' class='btn btn-default' onclick='move_section(this, 0)'><i class="fa fa-arrow-down"></i></button>
                </div>
              </div>
HTML;
          }
          echo "</section>\n";

          if($gen)
          {
            echo
            "<button type='button' class='btn btn-default add_section_btn' onclick='addSection($section_id)'>Add Section</button>";
          }
        }

        $sections->close();
      }
      if($section_count == 0 && $gen) {
          echo
          "<button type='button' class='btn btn-default add_section_btn' onclick='addSection($section_id)'>Add Section</button>";
      }

      $mysqli->close();
    }
  }

  function generate_add_section()
  {

    {
      echo <<<HTML
      <!-- <section class='section white-section add_section'>
      <button type='button' class='btn btn-default add_section_btn' onclick="addSection()"><h4>Add Section</h4></button>
      </section> -->
HTML;
    }
  }
 ?>
