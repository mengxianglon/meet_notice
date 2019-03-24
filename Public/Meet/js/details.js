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
  //跳转判断

  $('a').on('click',function(e){ 
    // 获取id号,得到是#box1 
    var target = $(this).prop('hash'); 
    if (target) {    
      //阻止a标签的默认行为跳转,这样就不会把#判断符带入到url中
       e = e || window.event; 
       e.preventDefault(); 
       //将页面滚动到对应的位置 
       //$("html,body").animate({scrollTop: $("tr#"+id).offset().top}, 500);
       //$('html,body').scrollTop($(target).offset().top); 
       $('html,body').animate({scrollTop: $(target).offset().top}, 500); 
    } 
  });
})

