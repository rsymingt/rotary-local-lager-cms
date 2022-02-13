$sections = {};
$animation_elements = {};
var prefix = "";

function size_to_viewport()
{
  $(".size_to_viewport").each(function(){
    $this = $(this);

    //window_height without header
    var window_height = $(window).height() - 195;

    $this.css({
      maxHeight: window_height+"px"
    });
  });
}

function setInput($input)
{
  setTimeout(function(){
    $($input).focus();
  },250);
}

function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

function check_nav(){
  $nav = $("#main-nav");
  if($nav.offset().top > 0)
  {
    $nav.addClass("scrolling");
  }else if($nav.hasClass("scrolling"))
  {
    $nav.removeClass("scrolling");
  }
  // $nav = $("#main-nav");
  // $head = $("#mast-head");
  // $li = $nav.find("li");
  //
  // if($nav.offset().top > $head.height() + 1){
  //   $li.addClass("scrolling");
  //   $nav.addClass("scrolling");
  // }else{
  //   if($li.hasClass("scrolling"))
  //   {
  //     $li.removeClass("scrolling");
  //   }
  // }
}

function contactForm(){
  $.confirm({
    title: 'Contact Form',
    content: 'url:'+prefix+'php/contact.php',
    columnClass: 'col-md-6',
    buttons: {
        formSubmit: {
            text: 'Submit',
            btnClass: 'btn-blue',
            action: function () {
                var name = this.$content.find('#name').val();
                var email = this.$content.find('#email').val();
                var subject = this.$content.find('#subject').val();
                var message = this.$content.find('#message').val();
                var form = this.$content.find('form');

                if(!name){
                    $.alert('provide a valid name');
                    return false;
                }else if(!email){
                    $.alert('provide a valid email');
                    return false;
                }else if(!subject){
                    $.alert('provide a valid subject');
                    return false;
                }else if(!message){
                    $.alert('provide a valid message');
                    return false;
                }
                // $.alert('Your name is ' + name);

                $.ajax({
                  url: 'php/mail.php',
                  type: 'POST',
                  data: {
                    name: name,
                    email: email,
                    subject: subject,
                    message: message
                  },
                  success: function(data){
                    $.alert(data);
                  }
                });
            }
        },
        cancel: function () {
            //close
        },
    },
    onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
    }
});
}

function scroll_if_anchor(href) {
   // alert(href.classList.contains(class));
    // href = typeof(href) == "string" ? href : $(this).attr("href");
    //
    // // You could easily calculate this dynamically if you prefer
    // var fromTop = $("#main-nav").height();
    //
    // // If our Href points to a valid, non-empty anchor, and is on the same page (e.g. #foo)
    // // Legacy jQuery and IE7 may have issues: http://stackoverflow.com/q/1593174
    // if(href != null && href.indexOf("#") == 0 && href != "#carousel-example-2") {
    //     var $target = $(href).closest(".section");
    //
    //     // Older browser without pushState might flicker here, as they momentarily
    //     // jump to the wrong position (IE < 10)
    //     if($target.length) {
    //         $('html, body').animate({ scrollTop: $target.offset().top - fromTop });
    //         if(history && "pushState" in history) {
    //             history.pushState({}, document.title, window.location.pathname + href);
    //             return false;
    //         }
    //     }
    // }
}

// function check_if_small(){
//   var width = $(window).width();
//
//   if(width < 1200)
//   {
//     $('.animation-element').addClass('mobile-delay');
//   }
//   else {
//     $('.animation-element').removeClass('mobile-delay');
//   }
// }

function in_view($el)
{
  var elementTop = $el.offset().top;
  var elementBottom = elementTop + $el.outerHeight();

  var viewportTop = $(window).scrollTop();
  var viewportBottom = viewportTop + $(window).height();

  return (elementBottom > viewportTop && elementTop < viewportBottom)
}

function check_if_in_view(){

  $sections.each(function(){
    var $this = $(this);
    if(in_view($this))
    {
      $section_elements = $animation_elements[$this.attr('id')];

      if($section_elements.length === 0)
        return true;

      var transitioning = false;
      $section_elements.each(function(){
        if($(this).hasClass("transitioning")){
          transitioning = true;
          return false;
        }
      });

      if(transitioning === true){
        return false;
      }

      var transitionDone = false;

      $section_elements.each(function(){
        $this = $(this);
        if($this.hasClass("transition-done") || $(this).hasClass("none")){
          transitionDone = true;
        }else if(in_view($this)){
          transitionDone = false;
          return false;
        }
      });

      if(transitionDone === true)
        return true;

      // $.alert(transitionDone + " ");
      if(transitionDone === false)
      {
        $section_elements.each(function(){
          $element = $(this);
          if(in_view($element) && !$element.hasClass("transition-done") && !$element.hasClass("none"))
          {
            $element.addClass('in-view');
            $element.addClass('transitioning');
            $element.one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function(){
              $this_element = $(this);
              $this_element.removeClass('transitioning');
              $this_element.addClass('transition-done')
              check_if_in_view();
            });
          }
        });
        return false;
      }
    }
  });
}

function fileExists(filename, success, fail)
{
  exists = false;
  $.ajax({
    type: "POST",
    url: "php/file_exists.php",
    data:{
      file: filename
    },
    error: function (){
      // alert("some error");
      fail();
    },
    success: function(data){
      if(data == "true")
      {
        success();
      }else {
        fail();
      }
    }
  });
  return exists;
}

function checkImageOverflow(){
  $("img").each(function(){
    $this = $(this);
    if($this.outerWidth(true) > $this.parent().width())
    {
      $this.css("margin", "0");
    }
  });
}

function ready($window){
  $("html").addClass("html-show");

  $sections = $($('#main-content .section').get());
  $sections.each(function(){
    $this = $(this);
    $animation_elements[$this.attr('id')] = $this.find('.animation-element');
  });

  $("body").on("click", "a", scroll_if_anchor);

  /* KIND OF OBSOLETE THINK OF REMOVING */
  $(".card a").each(function(){
    $this = $(this);
    if($this.text() === "Learn more")
    {
      $this.css({
        position: 'absolute',
        right:'10px',
        bottom:'10px'
      });
    }
  });

  $("img").each(function(){
    var image = $(this);
    var src = image.attr("src");
    if(src.indexOf("_low") != -1 && src.indexOf("img/") != -1){
      src = "../" + src.substr(0, src.indexOf("_low")) + src.substr(src.indexOf("_low") + 4);
      // alert(fileExists(src));
      // alert(src);
      // var data = image.data("img");
      fileExists(src, function(){
        src = src.substr(3);
        var loadimg = new Image();
        loadimg.onload = function(){
          image.addClass("noblur");
          image.attr('src', src);
          // image.removeAttr("data-img");
        };
        loadimg.src = src;
      }, function(){
        image.addClass("noblur");
      });
      // {
      // }
      // else {
      //   image.addClass("noblur");
      // }
    }
    else {
      image.addClass("noblur");
    }
    image.off('load');
  });

  $window.on('resize scroll', check_if_in_view);
  $window.on('resize scroll', check_nav);
  $window.on('resize', checkImageOverflow).trigger('resize');
  // $window.on('resize', scaleTables).trigger('resize');

  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    $("iframe").each(function(){
      $this = $(this);
      if($this.hasClass("no-mobile"))
      {
        $this.closest("section").hide();
        // $this.css({
        //   width: 0,
        //   height: 0
        // });
      }
    });
  }
}
