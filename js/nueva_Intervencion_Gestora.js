//Invocacion del archivo File Input para nueva intervencion coordinadora
$(function(){
    $('#nueva_intervencion_gestora').fileinput({
          language: 'es',
          'theme': 'fa',
          uploadUrl: '#',
          allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx',
          'xlsx', 'xls', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'mpeg4']
      });
  })
