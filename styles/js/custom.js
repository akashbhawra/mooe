$(document).ready(function() {

//Calling Ajax response in JSON
$.ajax({ // ajax call starts
          url: 'jsoneg.php', // JQuery loads serverside.php
          dataType: 'json', // Choosing a JSON datatype
          success: function (data) {// Variable data contains the data we get from serverside
            createtemplate(data);

          }});
});

//Create a template
var getpost = '';
function createtemplate(getpost) {
$.each( getpost, function( key, value ) {
  $.each( value, function( key, value ) {
    var template = $('<div class="'+key+'">'+value+'</div>');
    appentemplate(template);
  });
});
}


// Append template in to the DOM
var getTemplate = '';
function appentemplate(getTemplate) {
  $("#blogtemp").append(getTemplate);
}
