<?php

  function create_template($title, $description, $author, $folderTitle)
  {
      $html = '<?php
        session_start();
        $_SESSION["visitorUrl"] = "rotarylocallager.ca/news/'.$title.'";
        include("../../php/count.php");
        include("../../php/session.php");
        include("../../php/header-footer.php");
        include("../../php/sql_handler.php");
      ?>

      <!DOCTYPE html>
      <html>

      <head>
        <title>'.$title.'</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <meta name="description" content="'.$description.'">
        <meta name="author" content="'.$author.'">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="../../css/mdb.min.css" rel="stylesheet">
        <!-- Your custom styles (optional) -->

        <link href="../../css/jquery-confirm.min.css" rel="stylesheet">

        <link href="../../jquery-ui-1.12.1\jquery-ui.min.css" rel="stylesheet">

        <link href="../../css/stylesheet.css" rel="stylesheet">

        <script src="../../ckeditor/ckeditor.js"></script>

        <style>

        </style>

      </head>

      <body>

        <?php
          echo generateHeader("news");
         ?>

         <div id="main-content">

         <?php
            require_once("../../php/blog.php");
            load_post("'.$folderTitle.'", session_is_admin() || session_is_club());

            // echo "<p>$dir</p>";
          ?>
        </div>

         <?php
           echo generateFooter();
         ?>

         <!-- SCRIPTS -->
         <!-- JQuery -->
         <script type="text/javascript" src="../../js/rotary.js"></script>

         <script type="text/javascript" src="../../js/editor.js"></script>

         <script type="text/javascript" src="../../js/jquery-3.2.1.min.js"></script>

         <script src="../../jquery-ui-1.12.1\jquery-ui.min.js" type="text/javascript"></script>
         <!-- Bootstrap tooltips -->
         <script type="text/javascript" src="../../js/popper.min.js"></script>
         <!-- Bootstrap core JavaScript -->
         <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
         <!-- MDB core JavaScript -->
         <script type="text/javascript" src="../../js/mdb.min.js"></script>

         <script src="../../js/jquery-confirm.min.js" type="text/javascript"></script>

         <script type="text/javascript">
           var filename_noprefix = "news"; //db name for file!

           $window = $(window);

           function submit(el){
             $(el).submit();
           }

           $window.ready(function(){
             ready($window);

             $("#logout_btn").click(function(){
                 $.ajax({
                   url: "../../php/js_commands.php",
                   type:"POST",
                   data:{
                     action: "logout"
                   },
                   success: function(data){
                     location.reload();
                   }
                 });
             });
           });

         </script>

      </body>

      </html>
';

    return $html;
  }

?>
