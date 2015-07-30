// For Upload Field Type

// jQuery(document).ready(function($){
//     // Uploader
//     var uploadID = ''; /*setup the var*/
//     var mediaClicked = false;

//     jQuery('.upload-button').click(function() {
//         mediaClicked = true;
//         uploadID = jQuery(this).prev('input'); 
//         formfield = jQuery('.upload').attr('name');
//         tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
//         return false;
//     });

//     window.original_send_to_editor = window.send_to_editor;

//     window.send_to_editor = function(html) {
//         if (mediaClicked) {
//             imgurl = jQuery('img',html).attr('src');
//             uploadID.val(imgurl); 
//             tb_remove();
//             mediaClicked = false;
//         } else {
//             window.original_send_to_editor(html);
//         }
//     };
    
// });


jQuery(document).ready(function($){
  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

  $('.upload-button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    var id = jQuery(this).prev('input');
    _custom_media = true; 
    wp.media.editor.send.attachment = function(props, attachment){
      var size = props.size;
      var att =attachment.sizes[size];

      //props.size
      if ( _custom_media ) {
        $(id).val(att.url);
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open(button);
    return false;
  });

  $('.add_media').on('click', function(){
    _custom_media = false;
  });
});
