<?php
  if(isset($_POST['action']))
  {
    $a = $_POST['action'];

    if($a === 'session_is_admin')
    {
      session_start();
      require_once('session.php');
      if(session_is_admin())
      {
        echo true;
      }
      else {
        echo false;
      }
    }
    else if($a === 'session_is_club')
    {
      session_start();
      require_once('session.php');
      if(session_is_club())
      {
        echo true;
      }
      else {
        echo false;
      }
    }
    else if($a === 'logout'){
      session_start();
      require_once('session.php');
      session_logout();
    }
    else if($a === 'update_element'){ //updates element on sql database for page
      if(isset($_POST['db']) and isset($_POST['section']) and isset($_POST['element_id']) and isset($_POST['type']) and
      isset($_POST['animation']) and isset($_POST['data']) and isset($_POST['animation_delay'])){
        require_once('sql_handler.php');
        update_element($_POST['db'], $_POST['section'], $_POST['element_id'], $_POST['type'], $_POST['animation'], $_POST['data'], $_POST['animation_delay']);
      }
    }
    else if($a === 'update_section')
    {
      if(isset($_POST['db']) and isset($_POST['element_id']) and isset($_POST['type']) and isset($_POST['layout']) and isset($_POST['body']) and
      isset($_POST['article_color']) and isset($_POST['section']))
      {
        require_once('sql_handler.php');
        update_section($_POST['db'], $_POST['element_id'], $_POST['type'], $_POST['layout'], $_POST['body'], $_POST['article_color'], $_POST['section']);
      }
    }
    else if($a === 'remove_section')
    {
      if(isset($_POST['db']) and isset($_POST['element_id']))
      {
        require_once('sql_handler.php');
        remove_section($_POST['db'], $_POST['element_id']);
      }
    }
    else if($a === 'add_element')
    {
      if(isset($_POST['db']) and isset($_POST['section']) and isset($_POST['element_id']) and isset($_POST['type']) and isset($_POST['animation']) and isset($_POST['animation_delay']))
      {
        require_once('sql_handler.php');
        add_element($_POST['db'], $_POST['section'], $_POST['element_id'], $_POST['type'], $_POST['animation'], $_POST['animation_delay']);
      }
    }
    else if($a === 'remove_element')
    {
      if(isset($_POST['db']) and isset($_POST['section']) and isset($_POST['element_id']))
      {
        require_once('sql_handler.php');
        remove_element($_POST['db'], $_POST['section'], $_POST['element_id']);
      }
    }
    else if($a === "move_section")
    {
      if(isset($_POST['db']) and isset($_POST['section']) and isset($_POST['table']) and isset($_POST['up'])){
        require_once('sql_handler.php');
        move_section($_POST['db'], $_POST['table'], $_POST['section'], $_POST['up']);
      }
    }
    else if($a === "move_item")
    {
      if(isset($_POST['db']) and isset($_POST['section']) and isset($_POST['element_id']) and isset($_POST['right'])){
        require_once('sql_handler.php');
        move_item($_POST['db'], $_POST['section'], $_POST['element_id'], $_POST['right']);
      }
    }
  }
 ?>
