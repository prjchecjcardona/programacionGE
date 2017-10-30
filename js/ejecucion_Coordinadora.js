//Invocacion del archivo File Input Ejecucion Coordinadora
$(function(){
    $('#upload_coordinadora').fileinput({
          language: 'es',
          'theme': 'fa',
          uploadUrl: '#',
          allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx',
          'xlsx', 'xls', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'mpeg4']
      });
  })
  