<?php
//contact
  session_start();
  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message']))
  {
    $to = array(
      "ryanwsymington@gmail.com",
      "news@rotarylocallager.ca"
    );
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $from = $_POST['email'];
    $headers = 'From: '.$from . "\r\n" .
        'Reply-To: '.$from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();


    if(strlen($subject) > 0 && strlen($message)>0 && strlen($from)> 0 && strlen($name) > 0)
    {
      // echo "<script>alert('Email successfully sent!')</script>";
      $_SESSION['email-status'] = 'Email successfully sent!';
      foreach($to as $email)
      {
        if(!mail($email, $subject, $message, $headers))
          $_SESSION['email-status'] = 'Something went wrong. Your email was not sent!';
      }
    }
    else {
      // $_SESSION['email-status'] = 'Please fill in all the fields!';
      // echo "<script>alert('Something went wrong. Your email was not sent!')</script>";
    }
  }
  header("location: ../contact");

?>
