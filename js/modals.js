jQuery(document).ready(function() {

  jQuery("#modalUpdate").on('show.bs.modal', function(event) {
    var button = jQuery(event.relatedTarget);
    var url = button.attr("link");
    jQuery.post(url, "", 
      function(data) {
        jQuery("#modalUpdate").find(".modal-content").html(data);
      }
    );
  });

  // restore original modal-content from backup
  jQuery("#modalUpdate").on('hide.bs.modal', function(event) {
    jQuery("#modalUpdate").find(".modal-content").html(
      jQuery("#modalUpdate-orig").find(".modal-content").html()
    );
  });
  
  jQuery("#image-delete-confirm-dialog").on('show.bs.modal', 
    function(event) {
      var button = jQuery(event.relatedTarget);
      var image_id = button.attr("image-id");
      jQuery("#image-to-delete").val(image_id);
    }
  );  

  jQuery("#image-infeed-confirm-dialog").on('show.bs.modal',
    function(event) {
      var button = jQuery(event.relatedTarget);
      var image_id = button.attr("image-id");
      var image_infeed = button.attr("image-infeed");
      jQuery("#image-to-infeed").val(image_id);
      if (image_infeed > 0) {
        jQuery("#public-private").text("private");
        jQuery("#image-infeed-form-submit").text("Yes, make private");
      }
      else {
        jQuery("#public-private").text("public");
        jQuery("#image-infeed-form-submit").text("Yes, make public");
      }  
    }
  );  

}); // document.ready
