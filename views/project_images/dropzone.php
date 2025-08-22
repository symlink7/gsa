<script>
Dropzone.options.projectPhotoUpload = {
  dictDefaultMessage: "Click to Browse or Drag and Drop Files Here. Max "+
    "<?=$max_images?> file<?=($max_images == 1 ? "" : "s")?> this time.",
  paramName: "image_file", // The variable name for the file element
  parallelUploads: 1,
  maxFiles: <?=$max_images?>,
  maxFilesize: <?=$image_settings["max_size"]?>, // MB
  thumbnailWidth: <?=$image_settings["thumb_width"]?>,
  thumbnailHeight: <?=$image_settings["thumb_height"]?>,
  dictMaxFilesExceeded: "You can only upload a total of <?=$image_settings["max_images"]?> images.",
  //maxfilesreached: function(file) {
  //  alert("This is the last file you can upload.");
  //},
  maxfilesexceeded: function(file) {
    alert(dictMaxFilesExceeded);
  },  
  //sending: function(file) {
  //  alert("Will now be sending "+file.name);
  //},
  success: function(file, response) {
    var resp = jQuery.parseJSON(response);
    if (resp.error == 1) {
      var node, _i, _len, _ref, _results;
      var message = resp.msg; // modify it to your error message
      file.previewElement.classList.add("dz-error");
      _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        node = _ref[_i];
        _results.push(node.textContent = message);
      }
      return _results;
    }
    else {
      // alert(resp.msg);
      return file.previewElement.classList.add("dz-success");
    }  
  },
  <? if ($existing_images != "") { ?>
  init: function() {
    thisDropzone = this;
    var data = <?=$existing_images?>;
    jQuery.each(data, function(key, value) {
      var mockFile = { name: value.name , size: value.size };
      thisDropzone.options.addedfile.call(thisDropzone, mockFile);
      thisDropzone.options.thumbnail.call(thisDropzone, mockFile, 
        value.name);
    });
  }
  <? } ?>
};
</script>
