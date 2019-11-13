var Functions = Functions || {};
(function ($, Drupal, window, Functions) {
  'use strict';
  var postBackgroundHeader = drupalSettings.postBackgroundHeader;
  var post_color_scheme = drupalSettings.post_color_scheme;
  var pre_color_scheme = drupalSettings.pre_color_scheme;
  var utilityNavPostBackgroundHeader = drupalSettings.utility_nav_post_interaction_background;
  var utility_nav_post_color_scheme = drupalSettings.utility_nav_post_color_scheme;
  var utitlity_nav_pre_color_scheme = drupalSettings.utitlity_nav_pre_color_scheme;
  var search_color = drupalSettings.search_text_color;
  var search_background_color = drupalSettings.search_background_color;
  var mobile_device = 992;
  var tab_device = 991;
  var small_mobile_device = 575;
  var scroll_height = 50;
  var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
  var search_div_width = $('.block-novartis-dolphin-search').width();
  // Write Generic functions here which can be used throughout the file.
  Drupal.genericFunction = {
    scrollcheck: function (class_one, class_two) {
      if ($('.post-interaction-logo').length > 0) {
        $('.post-interaction-logo').addClass(class_one).removeClass(class_two);
        $('.pre-interaction-logo').addClass(class_two).removeClass(class_one);
      }
      else {
        $('.pre-interaction-logo').addClass('d-block');
      }
    },
    menucheck: function () {
      $('.menu-item--expanded > .wrapper > .trigger').removeClass('selected--menu');
      $('.header').addClass('desktop-post-' + postBackgroundHeader).addClass('desktop-post-' + post_color_scheme);
      $('.header--elements').addClass('desktop-post-' + utilityNavPostBackgroundHeader).addClass('desktop-post-' + utility_nav_post_color_scheme);
      $('#main-wrapper').removeClass('desktop-post-' + utilityNavPostBackgroundHeader).removeClass('desktop-post-' + utility_nav_post_color_scheme);
    },
    googlemap: function () {
      // For Google maps adding missing alt text (Fix for wave tool error)
      // Timeout for google maps to load on DOM
      setTimeout(function () {
        var images = document.querySelectorAll('.view-poi img');
        images.forEach(function (image, index) {
          image.alt = 'google-maps-image-' + index;
        });
      }, 1000);
    },
    backgroundOpacityHandler: function (params, checkId) {
      // To add opacity into the background color
      var check_color;
      var background_convertor;
      var get_color;
      var color_convertor;
      // To check if the key is pressed or no.
      if (!checkId) {
        for (var i = 0; i < $('.paragraph--type--search-block').length; i++) {
          if ($('.paragraph--type--search-block input.form-autocomplete')[i]) {
            check_color = $('.paragraph--type--search-block input.form-autocomplete')[i].id;
            background_convertor = $(('.paragraph--type--search-block input.form-autocomplete' + '#' + check_color)).css('background-color');
            // Check if search-embed-opacity class exist in the list
            if ($('.paragraph--type--search-block')[i].classList[1].includes('search-embed')) {
              get_color = $('.paragraph--type--search-block')[i].classList[1];
            }
            // This is used to Split the background color object and add opacity provided into RGBA color.
            background_convertor = background_convertor.split(')').slice(0, -1)[0].concat(', ' + get_color.split('-').pop() / 10 + ')');
            $(params + '#' + check_color)[0].style.backgroundColor = background_convertor.toString();
            if ($('.paragraph--type--search-block')[i].classList[4] && $('.paragraph--type--search-block')[i].classList[4].includes('border-radius')) {
              // This is used to Split the background color object and add opacity provided into RGBA color.
              var set_border = $('.paragraph--type--search-block')[i].classList[4].split('-').pop().toString();
              $($(params + '#' + check_color))[0].style.borderRadius = set_border + 'px';
            }
          }
        }
      }
      // For keypress event
      else {
        $('.search-api-autocomplete-search').attr('check-color', checkId);
        color_convertor = $(('.paragraph--type--search-block input.form-autocomplete' + '#' + checkId)).css('color');
        background_convertor = $(('.paragraph--type--search-block input.form-autocomplete' + '#' + checkId)).css('background-color');
        // This is used to Split the background color object and add opacity provided into RGBA color.
        $($(params + '[' + 'check-color' + '=' + checkId + ']')).css({'background-color': background_convertor.toString(), 'color': color_convertor});
      }
    },
    iosMobileScrollHandler: function () {
      if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
        iosScroll();
      }
      function iosScroll() {
        var header = document.getElementById('header');
        var scrollPosition = null; // Y position on touch start
        function disableScroll(event) {
          // Get the touch
          var clientY = event.targetTouches[0].clientY - scrollPosition;
          if (header.scrollTop === 0 && clientY > 0) {
            // element is at the top of its scroll
            event.preventDefault();
          }
          if (header.scrollHeight - header.scrollTop <= header.clientHeight && clientY < 0) {
            // element is at the top of its scroll
            event.preventDefault();
          }
        }
        header.addEventListener('touchstart', function (event) {
          if (event.targetTouches.length === 1) {
            // detect single touch
            scrollPosition = event.targetTouches[0].clientY;
          }
        }, false);

        header.addEventListener('touchmove', function (event) {
          if (event.targetTouches.length === 1) {
            // detect single touch
            disableScroll(event);
          }
        }, false);
      }
    }
  };
  Functions.onload = function () {
    Drupal.genericFunction.iosMobileScrollHandler();
    if ($('.paragraph--type--search-block input.form-autocomplete').length) {
      Drupal.genericFunction.backgroundOpacityHandler('.paragraph--type--search-block input.form-autocomplete');
    }
    $('ul[id*="ui-id-"].ui-widget.ui-widget-content.ui-front.search-api-autocomplete-search').css('max-width', search_div_width + 'px');
  };
  Functions.onKeyPress = function () {
    $('.paragraph--type--search-block input.form-autocomplete').keyup(function (e) {
      if ($.trim($(this).val()) !== '') {
        $('.search-api-autocomplete-search').addClass('search_block_embedded' + ' ' + search_background_color);
        Drupal.genericFunction.backgroundOpacityHandler('.search-api-autocomplete-search', e.currentTarget.id);
      }
    });
    $('header .ui-autocomplete-input').keyup(function () {
      if ($.trim($(this).val()) !== '') {
        var check_header_color = $('header .ui-autocomplete-input').css('color');
        var check_header_background_color = $('header .wrapper-search-text').css('background-color');
        $('.search-api-autocomplete-search').removeClass(search_color + ' ' + 'search_block_embedded' + ' ' + search_background_color);
        $('.search-api-autocomplete-search').css({'background-color': check_header_background_color, 'color': check_header_color});
      }
    });
  };
  Functions.slickslider = function () {
    if ($(window).width() < mobile_device) {
      $.fn.removeClassStartingWith = function (filter) {
        $(this).removeClass(function (index, className) {
          return (className.match(new RegExp('\\S*' + filter + '\\S*', 'g')) || []).join(' ');
        });
        return this;
      };

      $('.paragraph-slider-slickslider').each(function () {
        $(this).find('.row > div').removeClassStartingWith('col-');
      });
      $('.slickslider').not('.slick-initialized').slick({
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    }
  };

  Functions.headerLogo = function () {
    Drupal.genericFunction.googlemap();
    $('.header ul li, .menu-button').click(function () {
      // To avoid scrolling of body when the menu is clicked on mobile device
      if ($(window).width() < mobile_device) {
        if ($('.header').hasClass('height--fixed')) {
          $('body').addClass('fixed-body');
          if (isSafari) {
            $('body').css('position', 'absolute');
          }
        }
        else {
          $('body').removeClass('fixed-body');
          if (isSafari) {
            $('body').css('position', 'static');
          }
        }
      }
      var pre_color_scheme = drupalSettings.pre_color_scheme;
      if ((($(window).width() > tab_device && $("header[class*='post-']").length > 0) ||
        ($(window).width() <= tab_device && ($("header[class*='post-']").length > 0 &&
        $(window).scrollTop() > 0 && $("header[class*='desktop-']").length <= 0) || $("header[class*='desktop-']").length > 0 &&
        $("header[class*='scroll--overlay']").length > 0 || $("header[class*='height--fixed']").length > 0))) {
        Drupal.genericFunction.scrollcheck('d-block', 'd-none');
      }
      else if (($("header[class*='post-']").length === 0 &&
        $(window).width() > tab_device) || ($(window).width() <= tab_device && ($("header[class*='post-']").length > 0
        && $("header[class*='desktop-']").length > 0 && $(window).scrollTop() <= 0) || $("header[class*='post-']").length === 0)) {
        $('.header').addClass(pre_color_scheme);
        Drupal.genericFunction.scrollcheck('d-none', 'd-block');
      }
    });
  };
  // Scroll is checked at 50, it adds affix and post background settings class when scroll is past 50px in case of the header is sticky.
  Functions.scrollheader = function () {
    $('header.affix').addClass('scroll--overlay').removeClass('default--header');
    // Adding body scroll for cross browser support.
    $('body').scroll(function () {
      $('.ui-widget-content.ui-autocomplete').css('display', 'none');
    });
    $(window).scroll(function () {
      $('.lang .dropdown-menu').removeClass('show');
      $('.lang').removeClass('show');
      $('.ui-widget-content.ui-autocomplete').css('display', 'none');
      var scroll = $(this).scrollTop();
      var head = ('header.affix .desktop--only .main--navigation');
      // check on Generic case when mobile menu is open.
      if ($(window).width() < mobile_device) {
        if ($('.header').hasClass('post-header')) {
          $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
        }
        else {
          $('.header').removeClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
        }
      }
      if (scroll > scroll_height) {
        $('body').removeClass('affix');
        Drupal.genericFunction.scrollcheck('d-block', 'd-none');
        $('header.affix').addClass('scrolled--down').removeClass('scroll--overlay');
        $(head).find('ul').removeClass('show--menu');
        $(head).find('.trigger').removeClass('selected--menu');
        $(head).find('li').removeClass('active');
        $(head).find('li').removeClass('clicked-open');
        $('#navbar-main .lang ul').removeClass('show');
        $('.header').removeClass('mobile--sticky');
        if ($('header.affix').hasClass('scrolled--down')) {
          $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme).removeClass(pre_color_scheme);
        }
        if ($('.header').hasClass('default--header') && $('.menu-item--expanded > .trigger').hasClass('selected--menu')) {
          $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
        }
        if ($('.header').hasClass('scrolled--down')) {
          $('.header, #main-wrapper').removeClass('header-overlay');
        }
      }
      else {
        // Check when scrolled on top while submenu is clicked and scrolls on top.
        if ($('.header').hasClass('scrolled--down')) {
          if ($('.navbar').hasClass('post-header') && $(window).width() < mobile_device) {
            $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
          }
          else {
            $('.header').removeClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
          }
          $('.header').addClass(pre_color_scheme);
          Drupal.genericFunction.scrollcheck('d-none', 'd-block');
        }
        if ($('.show--menu').length === 0) {
          $('header.affix').addClass('scroll--overlay').removeClass('scrolled--down');
        }
        $('.header #navbar-main .menu--level--one').removeClass('show--menu');
        $('.header #navbar-main .menu-item .trigger').removeClass('selected--menu');
        if ($('.header').hasClass('default--header') && $('.menu-item--expanded > .trigger').hasClass('selected--menu')) {
          $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
        }
        if ($('.header.affix').hasClass('scroll--overlay') && !$('.menu-item--expanded > .trigger').hasClass('selected--menu')) {
          $('.header').removeClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
          $('.header').removeClass('desktop-post-' + postBackgroundHeader).removeClass('desktop-post-' + post_color_scheme);
          $('.header--elements').removeClass('desktop-post-' + utilityNavPostBackgroundHeader).removeClass('desktop-post-' + utility_nav_post_color_scheme);
        }
        if ($('.header.affix').hasClass('scroll--overlay')) {
          $('.header').addClass(pre_color_scheme);
        }
        if ($('.region-banner').length || $('.paragraph--type--hero-slider').length) {
          $('.header, #main-wrapper').addClass('header-overlay');
        }
      }
      if ($('.header').hasClass('height--fixed') && $('.header').hasClass('scroll--overlay')) {
        $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
      }
    });
  };

  // To add class on simpletext if search block is added next to it.
  Functions.embeddClass = function () {
    if ($('.paragraph--type--simple-text').next('.paragraph--type--search-block').length) {
      $('.paragraph--type--simple-text').addClass('has-search-block');
    }
  };

  Functions.mainmenu = function (context) {
    if (context !== document) {
      return;
    }
    $('.level--zero.menu-item--expanded').mouseenter(function (event) {

      if ($(window).width() < mobile_device) {
        return false;
      }
      else {
        $(this).siblings().find('ul').removeClass('show--menu');
        $(this).siblings().removeClass('active').removeClass('clicked-open');
      }

      if (!$(this).find('.menu--level--one').hasClass('show--menu') && (!($(this).hasClass('clicked-open')))) {
        Drupal.genericFunction.menucheck();
        $(this).addClass('active');
        $(this).children('ul').addClass('show--menu');
      }
    });

    $('.level--zero.menu-item--expanded').mouseleave(function () {
      if (!($(this).hasClass('clicked-open'))) {
        if (!$('.header').hasClass('scrolled--down')) {
          $('.header').removeClass('desktop-post-' + post_color_scheme).removeClass('desktop-post-' + postBackgroundHeader).addClass('scroll--overlay').addClass(pre_color_scheme);
          $('.header--elements').removeClass('desktop-post-' + utility_nav_post_color_scheme).removeClass('desktop-post-' + utilityNavPostBackgroundHeader);
        }
        $(this).removeClass('active');
        $(this).children('ul').removeClass('show--menu');
        if (!$('.header').hasClass('affix')) {
          $('.header').removeClass('post-' + postBackgroundHeader);
        }
      }
    });

    $('.menu-item--expanded .arrow-wrapper').hover(function () {
      return false;
    });

    $('header').ready(function () {
      var post_color_scheme = drupalSettings.post_color_scheme;
      $('.header').addClass('desktop-active-post-' + post_color_scheme);
    });
    $('.menu-item--expanded .arrow-wrapper').once().click(function (event) {
      if ($(window).width() > mobile_device) {
        $(this).parents('.level--zero').siblings().find('ul').removeClass('show--menu');
        $(this).parents('.level--zero').siblings().removeClass('active').removeClass('clicked-open');
        $(this).parent().parent().siblings().find('ul').removeClass('show--menu');
        if (!$('.header').hasClass('affix')) {
          $('.header').removeClass('post-' + postBackgroundHeader);
        }
      }

      if (!$(this).parent().parent().find('.menu--level--one').is(':visible')) {
        Drupal.genericFunction.menucheck();
        $(this).siblings().addClass('selected--menu');
        $(this).parent().parent().toggleClass('active');
        $('.header').removeClass('scroll--overlay').removeClass(pre_color_scheme);
        $(this).parent().parent().toggleClass('clicked-open');
      }
      else {
        $(this).parent().parent().removeClass('clicked-open');
        $(this).siblings().removeClass('selected--menu');
        if (!$('.header').hasClass('scrolled--down')) {
          $('.header').removeClass('desktop-post-' + post_color_scheme).removeClass('desktop-post-' + postBackgroundHeader);
          $('.header--elements').removeClass('desktop-post-' + utility_nav_post_color_scheme).removeClass('desktop-post-' + utilityNavPostBackgroundHeader);
          $('.header').addClass('scroll--overlay').addClass(pre_color_scheme);
        }
        $(this).parent().parent().removeClass('active');
      }
      $(this).parent().next().next().toggleClass('show--menu');
    });
    // On menu button of mobile click add class in order to fix the post and pre interaction state.
    $('.menu-button.navbar-toggler').click(function () {
      $('nav').toggleClass('post-header');
    });
    $('header .menu-button').once().click(function () {
      $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
      if ($(window).scrollTop() <= 0) {
        $('body').removeClass('affix');
      }
      if ($(window).scrollTop() <= scroll_height && $('.region-mobile-header').is(':visible') || $('header').hasClass('post-header')) {
        $('.header').toggleClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
      }
      if (($('.mobile--menu--expand.collapse.show').length > 0) && $(window).scrollTop() > scroll_height) {
        $('.header').addClass('post-' + postBackgroundHeader + ' ' + 'post-' + post_color_scheme);
      }
      $('.header').removeClass('scroll--overlay, mobile--sticky');
      $('header.affix').toggleClass('height--fixed');
    });
    if ($(window).width() < mobile_device) {
      $('.header--elements').removeClass(utitlity_nav_pre_color_scheme);
    }

    $('.mobile--menu--expand .lang a').once().click(function () {
      $(this).toggleClass('show');
      $(this).next().toggleClass('show');
      $('.main--navigation').find('li').removeClass('active');
    });

    $('#navbar-main .lang a').click(function () {
      var head = ('header.affix .desktop--only .main--navigation');
      $('.lang ul').addClass('post-' + postBackgroundHeader);
      $(head).find('ul').removeClass('show--menu');
      $('.header').removeClass('desktop-post-' + postBackgroundHeader).removeClass('desktop-post-' + post_color_scheme);
    });

    $('.menu--level--one li.menu--one').each(function (index, element) {
      $(this).children('.field--name-field-menu-brief-description').prependTo($(this).children('ul.menu-level-2'));
    });

    $('#main-wrapper.affix--fixed').css('padding-top', $('header.affix').outerHeight() + 'px');

    if ($('.header').hasClass('default--header')) {
      $('body').addClass('non-sticky');
    }

    var path = window.location.href;
    $('.level--zero > span.trigger a').each(function () {
      if (this.href === path) {
        $(this).addClass('nav--active');
      }
    });
  };

  Functions.headeroverlay = function (context) {

    if (context !== document) {
      return;
    }

    if ($('.region-banner').length || $('.paragraph--type--hero-slider').length) {
      var headerheight = $('.header').outerHeight(); // Height of header
      var banner = $('#main-wrapper .region-banner .paragraph--type--banner, #main-wrapper .search-banner-content');
      var topbannerpadding = parseInt(banner.css('padding-top'), 10);
      var toolbaaradjustment;

      if ($('#toolbar-administration').length) {
        toolbaaradjustment = $('#toolbar-administration')[0].getBoundingClientRect().height; // If admin toolbar is visible then add its height as padding-top
      }
      else {
        toolbaaradjustment = 0;
      }

      $('.header, #main-wrapper').addClass('header-overlay');
      banner.css('padding-top', headerheight + topbannerpadding + toolbaaradjustment + 'px');
      $('#main-wrapper.affix--fixed').css('padding-top', 0);

      if ($(window).width() > small_mobile_device) {
        var expertbanner = $('#main-wrapper .region-banner .node--type-expert');
        var topexpertbannerpadding = parseInt(expertbanner.css('padding-top'), 10);

        expertbanner.css('padding-top', headerheight + topexpertbannerpadding + 'px');
      }
    }
  };

  // Disabling double tap on search autocomplete selection for ios devices
  Functions.disabledoubletapios = function () {
    if ($('input[name="search"]').length) {
      $('input[name="search"]').autocomplete({
        open: function (event, ui) {
          if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
            $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
          }
        }
      });
    }
  };

  // Removing outline from link on IE browser in active state
  Functions.removeoutline = function () {
    var ua = window.navigator.userAgent;
    var isIE = /MSIE|Trident/.test(ua);

    if (isIE) {
      $(document).keydown(function () {
        $('.ie-last-clicked').removeClass('ie-last-clicked');
      });
      $('a').once('ie-last-clicked-processed').click(function () {
        $('.ie-last-clicked').removeClass('ie-last-clicked');
        $(this).addClass('ie-last-clicked');
      });
    }
  };

  // Swiping option for slider
  Functions.sliderSwipe = function () {
    $('.carousel-inner').swipe({
      // Generic swipe handler for all directions
      swipeLeft: function () {
        $(this).parent().carousel('next');
      },
      swipeRight: function () {
        $(this).parent().carousel('prev');
      },
      threshold: 0
    });
  };
})(jQuery, Drupal, window, Functions);
