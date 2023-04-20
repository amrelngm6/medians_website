$(document).ready(function() {

  var $preloader = $(".preloader");
  var afterPreloaderAT = 200;

  var scrolling = false;
  var curPage = 1;
  var numOfPages = $(".page").length;
  var $rotater = $(".rotater");
  var $iframes = $(".phone__iframes");
  var $phone = $(".phone");

  $(".preloader__overlay").on("animationend", function() {
    $preloader.addClass("inactive");
  });

  function afterPreloader() {
    $rotater.addClass("active");
    $phone.addClass("active");
    $(".page-1").addClass("active");
    $(".pagination").addClass("active");
    $rotater.css("transform", "rotate(0deg)");

    $(document).on("mousewheel DOMMouseScroll", scrollHandler);
    $(document).on("keydown", keydownHandler);
  };

  $preloader.on("transitionend", function() {
    $preloader.addClass("hidden");

    setTimeout(afterPreloader, afterPreloaderAT);
  });

  function pagination(page) {
    if (page) curPage = page;

    scrolling = true;
    $(".page.active").removeClass("active");
    $(".page-" + curPage).addClass("active");
    $(".pagination__item.active").removeClass("active");
    $(".pagination__item[data-page="+ curPage +"]").addClass("active");
    $rotater.css("transform", "rotate("+ (curPage - 1) * 180 +"deg)");
    $iframes.css("transform", "translate3d("+ (curPage - 1) * -100 +"%,0,0)");
  };

  function navigateUp() {
    if (curPage === 1) return;
    curPage--;
    pagination();
  };

  function navigateDown() {
    if (curPage === numOfPages) return;
    curPage++;
    pagination();
  };

  function scrollHandler(e) {
    if (scrolling) return;
    if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
      navigateUp();
    } else { 
      navigateDown();
    }
  };

  function keydownHandler(e) {
    if (scrolling) return;
    if (e.which === 38) {
      navigateUp();
    } else if (e.which === 40) {
      navigateDown();
    }
  };
  
  function paginationClickHandler() {
    if (scrolling) return;
    var $item = $(this);
    var page = $item.data("page");

    pagination(page);
  };
  
  $(document).on("click", ".pagination__item:not(.active)", paginationClickHandler);
  
  function paginationArrowHandler() {
    if (scrolling) return;
    if ($(this).hasClass("js-up")) {
      navigateUp();
    } else {
      navigateDown();
    }
  };
  
  $(document).on("click", ".pagination__arrow", paginationArrowHandler);

  $rotater.on("transitionend", function() {
    scrolling = false;
  });

});