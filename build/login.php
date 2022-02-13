<?php
session_start();
  require_once('php/session.php');
  if(session_logged_in())
  {
    header('location: ./');
  }
?>

<!DOCTYPE html>

<html>

<head>

  <title>Login | Rotary Local Lager</title>
  <!-- <meta name="description" content="Login page for Rotary Local Lager"> -->
  <meta name="author" content="Ryan Symington">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Bootstrap core CSS -->
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="./css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="./css/stylesheet.css" rel="stylesheet">

  <style>
    html,body,.container-fluid{
      margin: 0;
      padding: 0;
      height: 100%;
      width:100%;
    }

    .card{
      max-width: 400px;
      width: 90%;
      margin-left: 10px;
      margin-right: 10px;
      /*box-shadow: 25px 25px 25px 25px black;*/
    }

    form{
      padding: 25px;
    }

    #button{
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container-fluid{
      display: flex;
      align-items: center;
      justify-content: space-around;
    }
  </style>

</head>

<body>

  <?php
    if(isset($_POST['username']) && isset($_POST['password']))
    {
      if((isset($_SESSION['attempts']) && $_SESSION['attempts'] < 5) | !isset($_SESSION['attempts']))
      {
        $user = 'REDACTED';
        $pass = 'REDACTED';
        $db = 'REDACTED';
        $table = 'users';

        $username = $_POST['username'];
        $password = $_POST['password'];

        $mysqli = mysqli_connect("sql01.backboneservers.com", $user, $pass, $db);
        if(!$mysqli)
        {
          die('could not connect');
        }

        if($stmt = $mysqli->prepare("SELECT passwordHash, clearance FROM users WHERE username=?"))
        {
          $stmt->bind_param("s", $key);
          $stmt->bind_param("s", $username);

          $stmt->execute();

          $stmt->bind_result($passwordHash, $clearance);

          $stmt->fetch();

          if(password_verify($password, $passwordHash))
          {
              // echo "VALID!\n";
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['clearance'] = $clearance;
            $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];
            echo "<script>location='./'</script>";
          }

          $stmt->close();
        }

        $mysqli->close();

        if(isset($_SESSION['attempts']))
          $_SESSION['attempts'] ++;
        else
          $_SESSION['attempts'] = 1;

        if($_SESSION['attempts'] >= 5)
          $_SESSION['pass_attempt'] = $_SERVER['REQUEST_TIME'];
      }
      else
      {
        if(isset($_SESSION['pass_timeout']))
          $_SESSION['pass_timeout'] *= 2;
        else
          $_SESSION['pass_timeout'] = 30;

        $_SESSION['pass_attempt'] = $_SERVER['REQUEST_TIME'];
      }
    }
  ?>

  <div class="container-fluid">
    <div class="card">

      <div class="header pt-3 grey lighten-2">
          <div class="row d-flex justify-content-start">
              <h3 class="deep-grey-text mt-3 mb-4 pb-1 mx-5">Log in</h3>
          </div>
      </div>

      <form class="form-block" action="login.php" method="POST">

        <div class="md-form form-group">
          <i class="fa fa-lock prefix"></i>
          <input id="login-form" name="username" type="text" class="form-control validate">
          <label for="login-form">Type your Username</label>
        </div>

        <div class="md-form form-group">
          <i class="fa fa-lock prefix"></i>
          <input id="password-form" name="password" type="password" class="form-control validate">
          <label for="password-form">Type your Password</label>
        </div>

        <div id="button" class="md-form form-group">
            <button type="submit" class="btn btn-primary btn-lg z-depth-2">Login</button>
        </div>

        <?php

          if(isset($_SESSION['attempts']) && $_SESSION['attempts'] >= 5)
          {
            $timeout = (isset($_SESSION['pass_attempt']) && isset($_SESSION['pass_timeout'])) ? 
              $_SESSION['pass_timeout'] - ($_SERVER['REQUEST_TIME'] - $_SESSION['pass_attempt']) : 0;
            ?>
              <div class="md-form form-group">
                <h6 class="text-danger">Too many login attempts. Please try again later. <?php echo $timeout; ?>s</h6>
              </div>
            <?php
          }

        ?>

      </form>
    </div>
  </div>

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>

  <script>
    (function(){
      $("html").addClass("html-show");
    })();
  </script>

</body>

</html>
