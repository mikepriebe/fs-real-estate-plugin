//engage on the page load
$(function() {
  //trigger ajax on submit
  $('#contactForm').submit( function(){

    //hide the form
    $('#contactForm').hide();

    //show the loading bar
    $('.loader').append($('.bar'));
    $('.bar').css({display:'block'});

    //send the ajax request
    $.get('mail.php',{name:$('#name').val(),
                      email:$('#email').val(),
                      comment:$('#message').val()},

    //return the data
    function(data){
      //hide the graphic
      $('.bar').css({display:'none'});
      $('.loader').append(data);
    });

  //stay on the page
  return false;
  });
});