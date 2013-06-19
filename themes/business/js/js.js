function makeZoomOfic() {
$('.to_zoom').jqzoom({
  zoomType: 'innerzoom',
  zoomWidth: 300,
  zoomHeight: 250,
  title: false
});
}


function setAnchor() {
  // hide #back-top first
  $("#back-top").hide();
   // fade in #back-top
  $(function () {
    $(window).scroll(function () {
      if ($(this).scrollTop() > 100) {
        $('#back-top').fadeIn();
      } else {
        $('#back-top').fadeOut();
      }
    });

    // scroll body to 0px on click
    $('#back-top, #back-top a').click(function () {
      $('body,html').animate({
        scrollTop: 0
      }, 1500);
      return false;
    });
  });
}