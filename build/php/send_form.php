<?php
  //SIGNUP
  if(isset($_POST['store-address']) and isset($_POST['store-city']) and isset($_POST['store-num']) and
  isset($_POST['store-phone']) and isset($_POST['incorporated-name']) and isset($_POST['address']) and
  isset($_POST['city']) and isset($_POST['post-code']) and isset($_POST['contact']) and isset($_POST['contact-email']) and
  isset($_POST['contact-phone']) and isset($_POST['treasurer']) and isset($_POST['treasurer-email']) and isset($_POST['treasurer-phone']) and
  isset($_POST['secretary']) and isset($_POST['secretary-email']) and isset($_POST['secretary-phone']) and isset($_POST['meeting-loc']) and
  isset($_POST['date']) and isset($_POST['time']) and isset($_POST['street-num']) and isset($_POST['website']))
  {
    $contents = "";

    $contents = "STORE/DEPOT #,	Owning Club,	TYPE,	STREET #,	STREET ADDRESS,	CITY,	Servicing Depot, Club Number, Club Contact Name,	Contact Phone #,	Contact Email,	Treasurer Name,	Treasury Phone #,	Treasurer Email,	Secretary Name,	Secretary Phone #,	Secretary Email,	Address,	Postal Code, 	Website, 	Meeting Location, 	Day,	Time\r\n";
    // foreach ($_POST as $key => $value) {
    //     $contents .= $value . ",";
    // }
    $contents .= $_POST['store-num'].","."OWNING CLUB".","."TYPE".",".$_POST['street-num'].",".$_POST['store-address'].",".$_POST['store-city'].",".
                "SERVICING DEPOT".","."ROTARY NUM".",".$_POST['contact'].",".$_POST['contact-phone'].",".$_POST['contact-email'].
                ",".$_POST['treasurer'].",".$_POST['treasurer-phone'].",".$_POST['treasurer-email'].",".
                $_POST['secretary'].",".$_POST['secretary-phone'].",".$_POST['secretary-email'].",".
                $_POST['address'].",".$_POST['post-code'].",".$_POST['website'].",".$_POST['meeting-loc'].",".$_POST['date'].",".$_POST['time'];

    $contents = substr($contents, 0, strlen($contents)-1);

    $from_email         = 'news@rotarylocallager.ca'; //from mail, it is mandatory with some hosts
    $recipient_email    = 'ryanwsymington@gmail.com'; //recipient email (most cases it is your personal email)
    $subject = "form submission";
    $message = "new form submission";

    //Get uploaded file data
    $file_tmp_name = $filename;
    $file_name = "form.csv";
    $file_type = ".csv";

    $encoded_content = chunk_split(base64_encode($contents));

        $boundary = md5("sanwebe");
        //header
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From:".$from_email."\r\n";
        // $headers .= "Reply-To: ".$reply_to_email."" . "\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

        //plain text
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));

        //attachment
        $body .= "--$boundary\r\n";
        $body .="Content-Type: $file_type; name=".$file_name."\r\n";
        $body .="Content-Disposition: attachment; filename=".$file_name."\r\n";
        $body .="Content-Transfer-Encoding: base64\r\n";
        $body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";
        $body .= $encoded_content;

    $sentMail = @mail($recipient_email, $subject, $body, $headers);
    fclose($f);
    if($sentMail) //output success or failure messages
    {
      header("location: ../signup.php");
      die('Thank you for your email');
    }else{
      header("location: ../signup.php");
      die('Could not send mail! Please check your PHP mail configuration.');
    }

  }
  else{
    header("location: ../signup.php");
  }

 ?>
