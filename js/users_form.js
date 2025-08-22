jQuery(document).ready(function() {
  jQuery("select[name='user_role']").on("change", function() {
    if (jQuery(this).val() == 'S') {
      jQuery("div#user_project_type").show();
    }
    else {
      jQuery("div#user_project_type").hide();
    }  
  });
});
