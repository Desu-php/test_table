$(function(){
  $(".fold-table tr.view").on("click", function(){
    if($(this).hasClass("open")) {
      $(this).removeClass("open")
      $($(this).data('target')).removeClass('open')
    } else {
      // $(".fold-table tr.view").removeClass("open").next(".fold").removeClass("open");
      $(this).addClass("open")
      $($(this).data('target')).addClass('open')
    }
  });
});
