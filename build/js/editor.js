var prefix = "../";
var roxyFileman = '/fileman/index.html';

function enable_admin_features(){
  // $(".section:not(:last)").addClass('edit-mode');
  // $(".article").addClass("edit-mode");

  $('.content').click(function(){
    if($(this).data("edit") != "no" && !$(".note-editor")[0])
    {
      $this = $(this);
      var id = $this.attr('id');

      $this.parent().find('.section-bar').hide();
      // $this.parent().find('.btn-section-properties').hide();

      CKEDITOR.editorConfig = function( config )
      {
         // misc options
         // config.height = '100px';
         // config.height = '2000px';
         // config.width = ($this.parent().width()>400)?$this.parent().width():400;

         // config.pasteFromWordRemoveFontStyles = false;
         // config.pasteFromWordRemoveStyles = false;
         //
         // config.allowedContent = true;
      };

      CKEDITOR.replace( document.querySelector('#' + $this.attr('id')),  {
        // customConfig: 'config.js',
        language: 'en',
        uiColor: '#9AB8F3',
        // height: ($this.parent().height() < $window.height()-400)? $this.parent().height:$window.height()-400,
        height: $this.parent().height()+250,
        width: $this.parent().width(),
        allowedContent: true,
        pasteFromWordRemoveStyles: false,
        pasteFromWordRemoveFontStyles: false,
        disallowedContent: 'script; *[on*]',

      	// filebrowserBrowseUrl: ''+prefix+'ckfinder/ckfinder.html',
      	// filebrowserUploadUrl: ''+prefix+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserBrowseUrl:roxyFileman,
        filebrowserImageBrowseUrl:roxyFileman+'?type=image',
        removeDialogTabs: 'link:upload;image:upload'
      });

      $this.closest('article').addClass('editing');

      // $this.summernote('insertImage', u)
      $this.parent().append("<button id=\""+ id + "-save" +"\" type=\"button\" class=\"save-btn btn btn-default\" onclick=\"save(" + id + ")\">Save</button>");
    }
  });
}

function elfinderDialog() {
  	var fm = $('<div/>').dialogelfinder({
  		url : ''+prefix+'elFinder-2.1.31/php/connector.minimal.php', // change with the url of your connector
  		lang : 'en',
  		width : 840,
  		height: 450,
  		destroyOnClose : true,
  		getFileCallback : function(files, fm) {
  			// console.log(files);
  			$('.note-editor').parent().find('.content').summernote('insertImage', files.url, function($image){
          $image.css({
            maxWidth: '100%',
            height: 'auto'
          });
        });
  		},
  		commandsOptions : {
  			getfile : {
  			oncomplete : 'close',
  			folders : false
  			}
  		}
  	}).dialogelfinder('instance');
  }

function removeElement(el)
{
  $el = $(el);
  var yes = 0;

  $.confirm({
    title: 'STOP',
    content: 'Are you sure you want to delete ' + $el.attr('id'),
    buttons: {
      YES: function(){
        var element_id = $el.attr('id');
        var section_id = $el.closest('.section').attr('id');

        $.ajax({
          url: ''+prefix+'php/js_commands.php',
          type: 'POST',
          data:{
            action: 'remove_element',
            db: filename_noprefix,
            section: section_id,
            element_id: element_id
          },
          success: function(data){
          }
        });
        yes = 1;
      },

      NO: function(){
        // $.alert('NOT DELETED');
      }
    },
    onDestroy: function () {
       // when the modal is removed from DOM
       if(yes)
        location.reload();
    }
  });
}

function move_section(el, up)
{
  // ajax oncomplete
  var yes = 0;

  $section = $(el).closest('.section');

  var section_id = $section.attr('id');

  $.ajax({
    url: ''+prefix+'php/js_commands.php',
    type: 'POST',
    data:{
      action: 'move_section',
      db: filename_noprefix,
      table: 'sections',
      section: section_id,
      up: up
    },
    success: function(data){
      location.reload();
    }
  });
}

function move_item(el, right)
{
  var yes = 0;

  $el = $(el);

  var id = $el.attr('id');
  var section = $el.closest('.section').attr('id');

  $.ajax({
    url: ''+prefix+'php/js_commands.php',
    type: 'POST',
    data:{
      action: 'move_item',
      db: filename_noprefix,
      section: section,
      element_id: id,
      right: right
    },
    success: function(data){
      // $.alert(data);
      location.reload();
    }
  });
}

function updateElement(el)
{
  var yes = 0;

  $el = $(el);
  var element_id = $el.attr('id'),
      id = $el.closest('.section').attr('id'),
      data = $el.html();

  $.confirm({
    title: 'Item Properties (' + element_id  + ")",
    content: 'url: '+prefix+'dialogs/add_element.html',
    onContentReady: function(){
      var type = $el.data('type'),
          animation = $el.data('animation'),
          animation_delay = $el.data('animation_delay');

      this.$content.find('#sel1').val(type);
      this.$content.find('#sel2').val(animation);
      this.$content.find('#in1').val(animation_delay);

      // bind to events
      var jc = this;
      this.$content.find('form').on('submit', function (e) {
          // if the user submits the form by pressing enter in the field.
          e.preventDefault();
          jc.$$formSubmit.trigger('click'); // reference the button and click it
      });
    },
    buttons: {
      formSubmit:{
        text: 'Submit',
        btnClass: 'btn-blue',
        action: function(){
          var type = this.$content.find('#sel1').val(),
              animation = this.$content.find('#sel2').val(),
              animation_delay = this.$content.find('#in1').val();

          $.ajax({
            url: ''+prefix+'php/js_commands.php',
            type: 'POST',
            data: {
              action: 'update_element',
              db: filename_noprefix,
              section: id,
              element_id: element_id,
              type: type,
              animation: animation,
              data: data,
              animation_delay: animation_delay
            },
            success: function(data){
              // $.alert(data);
            }
          });
          yes = 1;
        }
      },
      cancel: function(){

      }
    },
    onDestroy: function(){
      if(yes) location.reload();
    }
  });
}

function addElement(el)
{
  var yes = 0;
  $.confirm({
    title:'Add Element',
    content: 'url: '+prefix+'dialogs/add_element.html',
    buttons: {
      formSubmit:{
        text: 'Submit',
        btnClass: 'btn-blue',
        action: function(){
          var type = this.$content.find('#sel1').val(),
              animation = this.$content.find('#sel2').val(),
              animation_delay = this.$content.find('#in1').val();

          $el = $(el).parent();


          var id = $el.attr('id');
          var idnum = 0;

          $("#" + id).find('.content').each(function(){
            var string = 'con_';
            var id = $(this).attr('id');
            var index = id.indexOf(string);
            var end = id.substr(index+string.length);
            if(idnum < parseInt(end))
            {
              idnum = parseInt(end);
            }
          });

          var new_id = id + "_con_" + (idnum+1);


          $.ajax({
            url: ''+prefix+'php/js_commands.php',
            type: 'POST',
            data: {
              action: 'add_element',
              db: filename_noprefix,
              section: id,
              element_id: new_id,
              type: type,
              animation: animation,
              animation_delay: animation_delay
            },
            success: function(data){
            }
          });
          yes = 1;
        }
      },
      cancel: function(){

      }
    },
    onContentReady: function () {
      // bind to events
      var jc = this;
      this.$content.find('form').on('submit', function (e) {
          // if the user submits the form by pressing enter in the field.
          e.preventDefault();
          jc.$$formSubmit.trigger('click'); // reference the button and click it
      });
    },
    onDestroy: function () {
       // when the modal is removed from DOM
       if(yes)location.reload();
    }
  });
}

function removeSection(el)
{
  var yes = 0;
  $el = $(el).closest('.section');

  $.confirm({
    title: 'STOP',
    content: 'Are you sure you want to delete ' + $el.attr('id'),
    buttons: {
      YES: function(){
        var id = $el.attr('id');

        $.ajax({
          url: ''+prefix+'php/js_commands.php',
          type: 'POST',
          data: {
            action: 'remove_section',
            db: filename_noprefix,
            element_id: id
          },
          success: function(data){
          }
        });
        yes=1;
      },

      NO: function(){
        // $.alert('NOT DELETED');
      }
    },
    onDestroy: function () {
       // when the modal is removed from DOM
       if(yes)location.reload();
    }
  });
}

 function session_is_admin(callback)
 {
   $.ajax({
     url: ''+prefix+'php/js_commands.php',
     type: 'POST',
     data: {
       action: 'session_is_admin'
     },
     success: function(data){
       callback(data);
     }
   });
 }

 function session_is_club(callback)
 {
   $.ajax({
     url: ''+prefix+'php/js_commands.php',
     type: 'POST',
     data: {
       action: 'session_is_club'
     },
     success: function(data){
       callback(data);
     }
   });
 }

function updateSection(el)
{
  var yes = 0;
  //get section (buttons parent)
  $el = $(el).closest('.section');

  var element_id = $el.attr('id'),
      type = $el.data('type'),
      layout = $el.data('layout'),
      body = $el.data('body'),
      actualType = $el.data('type'),
      a_color = $("#" + $el.attr('id') + "_article").data('article_color');

  $.confirm({
    title: 'Section Properties (' + element_id + ")",
    content: 'url: '+prefix+'dialogs/add_section.html',
    onContentReady: function(){
      this.$content.find('#sel1').val(type);
      this.$content.find('#sel2').val(layout);
      this.$content.find('#sel3').val(body);
      this.$content.find('#sel4').val(body);
      a_color = this.$content.find("#sel5").val(a_color);

      // bind to events
      var jc = this;
      this.$content.find('form').on('submit', function (e) {
          // if the user submits the form by pressing enter in the field.
          e.preventDefault();
          jc.$$formSubmit.trigger('click'); // reference the button and click it
      });
    },
    buttons:{
      formSubmit: {
        text: 'Submit',
        btnClass: 'btn-default',
        action: function(){
          type = this.$content.find('#sel1').val();
          layout = this.$content.find('#sel2').val();
          body = this.$content.find('#sel3').val();
          if(this.$content.find('#color_selector').is(":visible"))
            a_color = this.$content.find("#sel5").val();
          else {
            a_color = "transparent";
          }

          $.ajax({
            url: ''+prefix+'php/js_commands.php',
            type: 'POST',
            data: {
              action: 'update_section',
              db: filename_noprefix,
              element_id: element_id,
              type: type,
              layout: layout,
              body: body,
              article_color: a_color,
              section: 1
            },
            success: function(data){
              // $.alert(data);
              // location.reload();
            }
          });
          yes=1;
        }
      },

      cancel: function(){

      }
    },
    onDestroy: function(){
      if(yes) location.reload();
    }
  });
}

 function addSection(section)
 {
   var section_id = $(section).attr('id');
   if (section_id == null)
   {
     section_id = 1;
   }
   var yes = 0;
   $.confirm({
     title:'Add Section',
     content: 'url: '+prefix+'dialogs/add_section.html',
     buttons: {
       formSubmit: {
         text: 'Submit',
         btnClass: 'btn-blue',
         action: function(){
           var type = this.$content.find('#sel1').val(),
               layout = this.$content.find('#sel2').val(),
               body = this.$content.find('#sel3').val(),
               a_color = this.$content.find("#sel5").val();

          if(!this.$content.find('#color_selector').is(":visible"))
            a_color = "transparent";

          var idnum = 0;

          $("#main-content .section:not(.no-edit)").each(function(){
            var string = 'section_';
            var id = $(this).attr('id');
            var index = id.indexOf('section_');
            var end = id.substr(index+string.length);
            if(idnum < parseInt(end))
            {
              idnum = parseInt(end);
            }
          });

           var element_id = "section_" + (idnum + 1);

           // $.alert(element_id);

           $.ajax({
             url: ''+prefix+'php/js_commands.php',
             type: 'POST',
             data: {
               action: 'update_section',
               db: filename_noprefix,
               element_id: element_id,
               type: type,
               layout: layout,
               body: body,
               article_color: a_color,
               section: section_id
             },
             success: function(data){
               // $.alert(data);
               // location.reload();
             }
           });
           yes=1;
         }
       },

       cancel: function(){

       }
     },
     onContentReady: function () {
       // bind to events
       var jc = this;
       this.$content.find('form').on('submit', function (e) {
           // if the user submits the form by pressing enter in the field.
           e.preventDefault();
           jc.$$formSubmit.trigger('click'); // reference the button and click it
       });
     },
     onDestroy: function () {
        // when the modal is removed from DOM
        if(yes)location.reload();
     }
  });
 }

 function save(el)
 {
   $el = $(el);

   var section_id = $el.closest('.section').attr('id');
   var id = $el.attr('id');
   var type = $el.data('type');
   var animation = $el.data('animation');
   var animation_delay = $el.data('animation_delay');
   var data = CKEDITOR.instances[id].getData();
   // var data = $el.summernote('code');

   // alert(section_id + " " + id + " " + type + " " +animation + " " + data);
   // alert(filename);

   // ADD THE NEW CODE TO THE DATABASE

   $.ajax({
     url: ''+prefix+'php/js_commands.php',
     type: 'POST',
     data: {
       action: 'update_element',
       db: filename_noprefix,
       section: section_id,
       element_id: id,
       type: type,
       animation: animation,
       data: data,
       animation_delay: animation_delay
     },
     success: function(data)
     {
       // alert(data);
     }
   });

   $("#" + id + "-save").remove();

   // $el.summernote('destroy');
   CKEDITOR.instances[id].destroy();
   $(".dialogelfinder").remove();

   $this.parent().find('.section-bar').show();
   $this.closest('article').removeClass('editing');

   // location.reload();
 }

function logout(){
  $.ajax({
    url: ''+prefix+'php/js_commands.php',
    type:'POST',
    data:{
      action: 'logout'
    },
    success: function(data){
      location.reload();
    }
  });
}
