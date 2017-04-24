$(document).ready(function(){

  $('#file-upload-chat').on('change', function(){
    var fileObj = new FormData();
    fileObj.append('file', $('#file-upload-chat')[0].files[0]);
    fileObj.append('thread', $('#fileThreadId').val());
    fileObj.append('referenceId', $('#refIdNumber').val());
    fileObj.append('userId', $('#fileUserId').val());
    //fileObj.append('thread', $('#fileThreadId').val());

    console.log(fileObj);

    $.ajax({
      method: 'POST',
      url: '../Resources/PHP/Registros/uploadFile.php',
      data: fileObj,
      processData: false,
      contentType: false,
      success: function(result){
        console.log(result);
      },
      error: function(exception){
        console.warn(exception);
      }
    });
  });


});
