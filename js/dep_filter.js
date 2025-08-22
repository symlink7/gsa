jQuery(document).ready(function() {
  jQuery("select[name='dep_filter']").on("change", function() {
    if (jQuery(this).val() == '') {
      jQuery("div.project-panel").show();
    }
    else {
      jQuery("div.project-panel").hide();
      jQuery("div.dep-"+jQuery(this).val()).show();
    }  
  });
});

