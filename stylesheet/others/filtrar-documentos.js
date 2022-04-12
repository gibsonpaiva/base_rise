$("#filtrar-documentos").ready(function(){
  $("#input-documentos").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#documentos tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});