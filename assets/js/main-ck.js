function flagDropdownsWithMultipleItems(e){e.find("ul").each(function(){$(this).children().length>1&&$(this).addClass("multi")})}function resizeForSmallWindow(){$("html, body").css({"overflow-x":"hidden"});$(".sidebar-content").prependTo("footer");$("#searchwrap").appendTo(".navbar-inner");$("#searchform").hide()}function resizeForLargeWindow(){$(".wrap").css({"padding-left":"100px"});$("html, body").css({"overflow-x":"visible"});$(".sidebar-content").insertBefore("footer");$("#searchwrap").appendTo("#topbar .span3")}function checkWindowSize(){$(window).width()<1080?resizeForSmallWindow():resizeForLargeWindow();$(window).width()<480?mobileWindowSize():resizeForSmallWindow()}function menuscroll(){$(".btn-navbar").click(function(){$("html, body").scrollTop(0)})}function mobileWindowSize(){if($(window).width()<480){menuscroll();$("footer .mobile").insertAfter("#menu-primary-navigation-2")}else $("footer .mobile").insertAfter("#footer .nav-collapse")}function checkWidth(){var e=$(window).width();e>1080?$(".dropdown-off").removeClass("dropdown-off").addClass("dropdown"):e<=1079&&$(".dropdown").removeClass("dropdown").addClass("dropdown-off")}$(window).resize(checkWindowSize);$(window).resize(mobileWindowSize);$(document).ready(function(){function t(){if(e.hasClass("affix")){e.parent().addClass("affix-container");e.children("li:last-child").show()}else{e.children("li:last-child").hide();e.parent().removeClass("affix-container")}}checkWindowSize();mobileWindowSize();$("footer .sub-menu .sub-menu").remove();var e=$("#menu-primary-navigation");e.affix({offset:170});e.wrap("<div>");e.append("<li>"+$("<div>").append($("header .get-started").clone()).html()+"</li>");flagDropdownsWithMultipleItems(e);t();$(window).scroll(t);$(".post-more").append("<a href='#' class='read-less'>Read Less</a>");$(".main-content a.read-more").click(function(){$(".main-content .post-more").slideDown();$(".main-content .read-more").hide();return!1});$(".main-content a.read-less").click(function(){$("html, body").animate({scrollTop:$("#topbar").offset().top},1e3,"swing",function(){$(".main-content .post-more").hide();$(".main-content .read-more").show()});return!1});$(".pod-listing a.read-more").click(function(){$(this).next(".post-more").slideDown();$(this).hide();return!1});$(".pod-listing a.read-less").click(function(){var e=$(this);$("html, body").animate({scrollTop:e.parent().parent().offset().top-100},1e3,"swing",function(){e.parent().hide();e.parent().parent().find(".read-more").show()});return!1});var n=$(".other-categories li:first-child").position();$(".other-categories li").each(function(){n.top!=$(this).position().top&&$(this).prev().addClass("no-border");n=$(this).position()});$(".breadcrumbs").append("<span class='separator'>></span>");$("ul li:last-child").addClass("last");var r=$("#menu-top-navigation .menu-alteva-communications");r.find(".dropdown-toggle").removeClass("dropdown-toggle");r.find(".dropdown-menu").show();r.find(".caret").remove();$("img.hide-empty").each(function(){$(this).attr("src")!=""&&$(this).show()});$(".search-results .pager .previous a").text("More Results");$(".search-results .pager .next a").text("Previous Results")});checkWidth();$(window).resize(checkWidth);$(".searchmobile").click(function(){var e=$(this);e.toggleClass("open");$("#searchform").toggle(100);$(".in").toggle(100)});$(document).ready(function(){$(".carousel-inner").swipe({swipeLeft:function(e,t,n,r,i){$(this).parent().carousel("prev")},swipeRight:function(){$(this).parent().carousel("next")},threshold:0})});