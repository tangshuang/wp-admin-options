jQuery(function($) {
   //uploading files variable
   var custom_file_frame;
   jQuery(document).on('click', '.upload-media', function(event) {
     var $this = $(this),
        input_id = $this.attr('data-input-to'),
        $input = (input_id != undefined && jQuery(input_id).length > 0 ? jQuery(input_id) : $this.prev());
      event.preventDefault();
      //If the frame already exists, reopen it
      if (typeof(custom_file_frame)!=="undefined") {
         custom_file_frame.close();
      }

      //Create WP media frame.
      custom_file_frame = wp.media.frames.customHeader = wp.media({
         //Title of media manager frame
         title: "上传或选择图片",
         library: {
            type: 'image'
         },
         button: {
            //Button text
            text: "使用选中图片"
         },
         //Do not allow multiple files, if you want multiple, set true
         multiple: false
      });

      //callback for selected image
      custom_file_frame.on('select', function() {
         var attachment = custom_file_frame.state().get('selection').first().toJSON();
         //do something with attachment variable, for example attachment.filename
         //Object:
         //attachment.alt - image alt
         //attachment.author - author id
         //attachment.caption
         //attachment.dateFormatted - date of image uploaded
         //attachment.description
         //attachment.editLink - edit link of media
         //attachment.filename
         //attachment.height
         //attachment.icon - don't know WTF?))
         //attachment.id - id of attachment
         //attachment.link - public link of attachment, for example ""http://site.com/?attachment_id=115""
         //attachment.menuOrder
         //attachment.mime - mime type, for example image/jpeg"
         //attachment.name - name of attachment file, for example "my-image"
         //attachment.status - usual is "inherit"
         //attachment.subtype - "jpeg" if is "jpg"
         //attachment.title
         //attachment.type - "image"
         //attachment.uploadedTo
         //attachment.url - http url of image, for example "http://site.com/wp-content/uploads/2012/12/my-image.jpg"
         //attachment.width
         $input.val(attachment.url);
      });

      //Open modal
      custom_file_frame.open();
   });
});
