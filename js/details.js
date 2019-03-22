$(function () {
        var head=$("#banner").height();
        $(window).scroll(function(){
          var topScr=$(window).scrollTop();
          if (topScr>head) {
            $(".navigation").addClass("fixed");
          }else{
            $(".navigation").removeClass("fixed");
          }
        })
       

  $("#to_top").click(function(){
      $("html,body").animate({scrollTop:0},400);
  })  
})
