jQuery(document).ready(function(){

  jQuery(document).on("click", ".button", function(event) {
    var formId = jQuery(this).attr("name");
    // hide the submit button
    jQuery(this).hide();
 
    jQuery("#"+formId+"-please-wait").show();

    // reset old errors messages
    jQuery("#"+formId+"-error-msg").hide();
    jQuery("#"+formId+"-error-msg").html("");
    jQuery(".control-label").css({color:"#000"});
    
    jQuery("textarea.ckeditor").each(function() {
      var the_id = jQuery(this).attr("id");
      jQuery(this).val(CKEDITOR.instances[the_id].getData());
    });  
    
    var q = jQuery("#"+formId+"-form").serialize();
    // alert(q);
    jQuery.post("ajax.php", q, function(data) {
      // check for errors
      if (jQuery(data).find('error_msg').text()) {
        
        jQuery("#"+formId+"-error-msg").html(
          '<p>'+jQuery(data).find('error_msg').text()+'</p>');
        jQuery("#"+formId+"-error-msg").show();

        jQuery(data).find("error_fields").children().each(function(key, val){
          var red_id = jQuery(val).text();
          jQuery("#"+red_id+"_req").css({color:"red"});
        });
        
        jQuery("#"+formId+"-please-wait").hide();
        jQuery("#"+formId+"-form-submit").show();
      }  
      else if (jQuery(data).find("done").text()) {
        if (jQuery(data).find("redirect").text()) {
          jQuery("#"+formId+"-redirect").children().filter(":input[name=vars]").
            val(jQuery(data).find("redirect").text());
          jQuery("#"+formId+"-redirect").submit();  
        }
        else if (jQuery(data).find("refresh").text()) {
          jQuery("#"+formId+"-please-wait").hide();
          jQuery("#"+formId+"-success-msg").html(
            '<h3>'+jQuery(data).find("refresh").text()+'</h3>'+
            '<p>Reloading the page now, please wait!</p>'
          );
          jQuery("#"+formId+"-success-msg").show();  
          location.reload();
        }
        else {
          jQuery("#"+formId+"-form-div").hide();
          jQuery("#"+formId+"-form-done").show();
          jQuery("#page-title").focus();
          //jQuery("#"+formId+"-form-done").focus();
        }
        jQuery("#"+formId+"-please-wait").hide();
      } 
      else {
        jQuery("#"+formId+"-error-msg").html(
          '<p>Something went wrong...</p>');
        jQuery("#"+formId+"-error-msg").show();

        jQuery("#"+formId+"-please-wait").hide();
        jQuery("#"+formId+"-form-submit").show();
      }  
    }, "xml");
    event.preventDefault();
  });

  jQuery(".confirm-yes").click(function(event) {
    var formId = jQuery(this).attr("name");
    jQuery("#"+formId+"-form").submit();
  });  

  jQuery(".continue-project-add").click(function(event) {
    var vars = jQuery(this).attr("name");
    jQuery("#project-add-redirect").children().filter(":input[name=vars]").
      val(vars);
    jQuery("#project-add-redirect").submit();
    event.preventDefault();
  });

  jQuery(".approve-button").click(function(event) {
    url = jQuery(this).attr("href");
    jQuery.jGrowl("Submitting status for approval.<br />Please wait...", {
      sticky: true, closer: false,
      position: 'top-right', theme: 'bg-green',
      open: function() {
        obj = jQuery(this);
        jQuery.post("ajax.php", url, function(data) {
          if (jQuery(data).find('error_msg').text()) {
            jQuery(obj).children('.jGrowl-message').html(
              jQuery(data).find('error_msg').text()
            );
          }  
          else if (jQuery(data).find('refresh').text()) {
            jQuery(obj).children('.jGrowl-message').html(
              '<h3>'+jQuery(data).find("refresh").text()+'</h3>'+
              '<p>Reloading the page now, please wait!</p>'
            );
            location.reload();
          }
          else {  
            jQuery(obj).children('.jGrowl-message').html(
              '<p>Something went wrong...</p>'
            );
          }  
        }, "xml");  
      }
    });
    
    event.preventDefault();
  });

  jQuery(document).on('click',
    "#image-delete-form-submit", function(event) {
    var formId = jQuery(this).attr("name");
    jQuery.jGrowl("Deleting image.<br />Please wait...", {
      sticky: true, closer: false,
      position: 'top-right', theme: 'bg-yellow',
      open: function() {
        obj = jQuery(this);
        var q = jQuery("#"+formId+"-form").serialize();
        jQuery.post("ajax.php", q, function(data) {
          if (jQuery(data).find('error_msg').text()) {
            jQuery(obj).css({ 'background-color': 'red'});
            jQuery(obj).children('.jGrowl-message').html(
              jQuery(data).find('error_msg').text()
            );
          }
          else if (jQuery(data).find('refresh').text()) {
            jQuery(obj).css({ 'background-color': 'green'});
            jQuery(obj).children('.jGrowl-message').html(
              '<h3>'+jQuery(data).find("refresh").text()+'</h3>'+
              '<p>Reloading the page now, please wait!</p>'
            );
            location.reload();
          }
          else {
            jQuery(obj).css({ 'background-color': 'red'});
            jQuery(obj).children('.jGrowl-message').html(
              '<p>Something went wrong...</p>'
            );
          }
        }, "xml");
      }
    });
    event.preventDefault();
  });

  jQuery(document).on('click',
    "#image-infeed-form-submit", function(event) {
    var formId = jQuery(this).attr("name");
    jQuery.jGrowl("Changing status.<br />Please wait...", {
      sticky: true, closer: false,
      position: 'top-right', theme: 'bg-yellow',
      open: function() {
        obj = jQuery(this);
        var q = jQuery("#"+formId+"-form").serialize();
        jQuery.post("ajax.php", q, function(data) {
          if (jQuery(data).find('error_msg').text()) {
            jQuery(obj).css({ 'background-color': 'red'});
            jQuery(obj).children('.jGrowl-message').html(
              jQuery(data).find('error_msg').text()
            );
          }
          else {
            var image_id = jQuery("#image-to-infeed").val();
            icon = jQuery("i.infeed").filter("[image-id="+image_id+"]");
            var stat = jQuery(icon).attr("image-infeed");
            if (stat > 0) {
              jQuery(icon).removeClass("plug-on");
              jQuery(icon).addClass("plug-off");
              jQuery(icon).attr("image-infeed", 0);
            }
            else {
              jQuery(icon).removeClass("plug-off");
              jQuery(icon).addClass("plug-on");
              jQuery(icon).attr("image-infeed", 1);
            }
            jQuery(obj).remove();
          }
        }, "xml");
      }
    });
    event.preventDefault();
  });

  jQuery(".approve-statuses-collapse").on('show.bs.collapse',
    function(event) {
      var id = jQuery(this).attr("name");
      jQuery("#bulk-approve-"+id).show();
    }
  ); // on opening approve-statuses-collapse
  jQuery(".approve-statuses-collapse").on('hide.bs.collapse',
    function(event) {
      var id = jQuery(this).attr("name");
      jQuery("#bulk-approve-"+id).hide();
    }  
  ); // on hiding approve-statuses-collapse 

  jQuery("#bulk-approve-confirm-dialog").on('show.bs.modal', 
    function(event) {
      var button = jQuery(event.relatedTarget);
      var q = button.attr("link");
      var id = button.attr("name");
      var project_name = jQuery("#project-name-"+id).text();
      jQuery("#project-name-span").html(project_name);
      jQuery("#bulk-approve-confirm-yes").attr("href", q);
    }
  );  

  jQuery("#bulk-approve-confirm-yes").click(function(event) {
    url = jQuery(this).attr("href");
    jQuery.jGrowl("Submitting statuses for approval.<br />Please wait...", {
      sticky: true, closer: false,
      position: 'top-right', theme: 'bg-green',
      open: function() {
        obj = jQuery(this);
        jQuery.post("ajax.php", url, function(data) {
          if (jQuery(data).find('error_msg').text()) {
            jQuery(obj).children('.jGrowl-message').html(
              jQuery(data).find('error_msg').text()
            );
          }  
          else if (jQuery(data).find('refresh').text()) {
            jQuery(obj).children('.jGrowl-message').html(
              '<h3>'+jQuery(data).find("refresh").text()+'</h3>'+
              '<p>Reloading the page now, please wait!</p>'
            );
            location.reload();
          }
          else {  
            jQuery(obj).children('.jGrowl-message').html(
              '<p>Something went wrong...</p>'
            );
          }  
        }, "xml");
      }
    });
    event.preventDefault();
  });

jQuery.fn.modal.Constructor.prototype.enforceFocus = function() {
  modal_this = this
  jQuery(document).on('focusin.modal', function (e) {
    if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length 
    && !jQuery(e.target.parentNode).hasClass('cke_dialog_ui_input_select') 
    && !jQuery(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
      modal_this.$element.focus()
    }
  })
};

});
