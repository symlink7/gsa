$(document).ready(function(){

  $(document).on("click", ".button", function(event) {
    var formId = $(this).attr("name");
    // hide the submit button
    $(this).hide();
 
    $("#"+formId+"-please-wait").show();

    // reset old errors messages
    $("#"+formId+"-error-msg").hide();
    $("#"+formId+"-error-msg").html("");
    $(".control-label").css({color:"#000"});

    var q = $("#"+formId+"-form").serialize();
    $.post("ajax.php", q, function(data) {
      // check for errors
      if ($(data).find('error_msg').text()) {
        
        $("#"+formId+"-error-msg").html(
          '<p>'+$(data).find('error_msg').text()+'</p>');
        $("#"+formId+"-error-msg").show();

        $(data).find("error_fields").children().each(function(key, val){
          var red_id = $(val).text();
          $("#"+red_id+"_req").css({color:"red"});
        });
        
        $("#"+formId+"-please-wait").hide();
        $("#"+formId+"-form-submit").show();
      }  
      else if ($(data).find("done").text()) {
        if ($(data).find("redirect").text()) {
          $("#"+formId+"-redirect").children().filter(":input[name=vars]").
            val($(data).find("redirect").text());
          $("#"+formId+"-redirect").submit();  
        }
        else if ($(data).find("refresh").text()) {
          $("#"+formId+"-please-wait").hide();
          $("#"+formId+"-success-msg").html(
            '<h3>'+$(data).find("refresh").text()+'</h3>'+
            '<p>Reloading the page now, please wait!</p>'
          );
          $("#"+formId+"-success-msg").show();  
          location.reload();
        }
        else {
          $("#"+formId+"-form-div").hide();
          $("#"+formId+"-form-done").show();
          $("#page-title").focus();
          //$("#"+formId+"-form-done").focus();
        }
      } 
      else {
        $("#"+formId+"-error-msg").html(
          '<p>Something went wrong...</p>');
        $("#"+formId+"-error-msg").show();

        $("#"+formId+"-please-wait").hide();
        $("#"+formId+"-form-submit").show();
      }  
    }, "xml");
    event.preventDefault();
  });

/*
  $.fn.extend({
    submitForm: function() {
      var formId = $(this).attr("name");
      // hide the submit button
      $("#"+formId+"-form-submit").hide();
      $("#"+formId+"-please-wait").show();

      // reset old errors messages
      $("#"+formId+"-error-msg").hide();
      $("#"+formId+"-error-msg").html("");
      $(".control-label").css({color:"#000"});

      var q = $("#"+formId+"-form").serialize();
      $.post("ajax.php", q, function(data) {
        // check for errors
        if ($(data).find('error_msg').text()) {

          $("#"+formId+"-error-msg").html(
            '<p>'+$(data).find('error_msg').text()+'</p>');
          $("#"+formId+"-error-msg").show();

          $(data).find('error_fields').children().each(function(key, val){
            var red_id = $(val).text();
            $("#"+red_id+"_req").css({color:"red"});
          });

          $("#"+formId+"-please-wait").hide();
          $("#"+formId+"-form-submit").show();
        }
        else if ($(data).find('done').text()) {
          $("#"+formId+"-form-div").hide();
          $("#"+formId+"-form-done").show();
        }
        else {
          $("#"+formId+"-error-msg").html(
            '<p>Something went wrong...</p>');
          $("#"+formId+"-error-msg").show();

          $("#"+formId+"-please-wait").hide();
          $("#"+formId+"-form-submit").show();
        }
      }, "xml");
    } // submitForm function    
  }); // jQuery.fn.extend({})
*/
  $(".confirm-yes").click(function(event) {
    var formId = $(this).attr("name");
    $("#"+formId+"-form").submit();
  });  

  $(".continue-project-add").click(function(event) {
    var vars = $(this).attr("name");
    $("#project-add-redirect").children().filter(":input[name=vars]").
      val(vars);
    $("#project-add-redirect").submit();
    event.preventDefault();
  });

/*
  $(".confirm-button").click(function(event) {
    event.preventDefault();
    var formId = $(this).attr("name");
    $("#"+formId+"-confirm-dialog").dialog("open");
  });

  $(".confirm-dialog").dialog({
    dialogClass: "no-close",
    buttons: [
      {
        text: "Yes",
        click: function() {
          var formId = $(this).attr("name");
          $("#"+formId+"-form").submit();
          // $(this).dialog("close");
        }
      }, // Yes button
      {
        text: "No",
        click: function() {
          $(this).dialog("close");
        }
      } // No button
    ]
  });
*/
});
