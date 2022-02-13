<?php
function session_logged_in()
{
  $time = $_SERVER['REQUEST_TIME'];

  $timeout_duration = 30 * 60;
  
  if(!isset($_SESSION['pass_timeout']))
    $_SESSION['pass_timeout'] = 30;

  $pass_timeout = $_SESSION['pass_timeout'];

  if(isset($_SESSION['pass_attempt']) &&
    ($time - $_SESSION['pass_attempt']) > $pass_timeout)
  {
    unset($_SESSION['attempts']);
    unset($_SESSION['pass_attempt']);
    unset($_SESSION['pass_timeout']);
  }

  if(isset($_SESSION['LAST_ACTIVITY']) and
  ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration)
  {
    session_unset();
    session_destroy();
    session_start();
  }
  else {
    $_SESSION['LAST_ACTIVITY'] = $time;
  }

  if(isset($_SESSION['loggedin']) and $_SESSION['loggedin'] === true)
  {
    return true;
    // header('location: login.php');
  }
  return false;
}

function session_get_username(){
  if(isset($_SESSION['username']))
    return $_SESSION['username'];
}

function session_is_admin(){
  if(isset($_SESSION['loggedin']) and isset($_SESSION['username'])  && isset($_SESSION['clearance']) && $_SESSION['clearance'] == 0)
    return true;
  return false;
}

function session_is_club(){
  if(isset($_SESSION['loggedin']) and isset($_SESSION['username']) && isset($_SESSION['clearance']) && $_SESSION['clearance'] == 1)
    return true;
  return false;
}

function session_logout(){
    session_unset();
    session_destroy();
    session_start();
}
?>
