// For specificity in terms of not checking the actual Drupal object but checking the reference of it.
var Drupal = Drupal;
var Functions = Functions;
var uidialogOne;
var uidialogTwo;
(function ($, Drupal, window, Functions, drupalSettings) {
  'use strict';
  // checking if Drupal behavior is loaded, else checking normal for patternlab.
  if (typeof (Drupal) != 'undefined') {
    Drupal.behaviors.slickslider = {
      attach: Functions.slickslider
    };

    Drupal.behaviors.borderRadius = {
      attach: function (context, settings) {
        var imageRadius = drupalSettings.imageRadius;
        var inputBorderRadius = drupalSettings.inputBorderRadius;
        var dropdownBorderRadius = drupalSettings.dropdownBorderRadius;
        var novartisDolphinExpertcta = drupalSettings.novartisDolphinExpertcta;
        $('.cta--expert img, .cta--expert .expert-gradient').css('border-radius', novartisDolphinExpertcta);
        $('.field--name-field-page-components .field--name-field-image img').css('border-radius', imageRadius);
        $('.form-type-textfield .form-text, .form-textarea, .form-email, .form-tel, .form-url, .form-number').css('border-radius', inputBorderRadius);
        setTimeout(function () {
          $('.chosen-container').css('border-radius', dropdownBorderRadius);
        }, 1000);
      }
    };
    Drupal.behaviors.searcheader = {
      attach: function (context, settings) {
        // For Adding class dynamically to the Auto Complete Fields.
        if ($('.form-autocomplete.form-text.ui-autocomplete-input').length > 0) {
          var formautocomplete = $('.form-autocomplete.form-text.ui-autocomplete-input').attr('search-header');
          $('.ui-menu.ui-autocomplete').addClass(formautocomplete);
        }
      }
    };
    Drupal.behaviors.touchMoveComponent = {
      attach: function (context, settings) {
        $('body').keydown(function (e) {
          if (e.keyCode === 27) {
            $('body').css('position', 'relative').css('overflow', 'auto');
          }
        });
        $('body').click(function () {
          if ($('.ui-dialog').is(':visible')) {
            $('body').css('overflow', 'hidden');
          }
          else {
            $('body').css('overflow', 'auto');
          }
        });
        uidialogOne = document.getElementsByClassName('ui-dialog');
        uidialogTwo = document.getElementsByClassName('ui-widget-overlay');
        $('li').click(function () {
          if ($('.ui-dialog:visible')) {
            uiDialogBox(uidialogOne);
            uiDialogBox(uidialogTwo);
          }
        });
        function uiDialogBox(uidialog) {
          Array.from(uidialog).forEach(function (dialog) {
            dialog.addEventListener('touchstart', function touchStartHandler(e) {
              dialog.removeEventListener('touchstart', touchStartHandler, false);
            }, false);
            dialog.addEventListener('touchmove', function (e) {
              e.preventDefault();
            }, {passive: false});
          });
        }
      }
    };

    Drupal.behaviors.scrolllink = {
      attach: function () {
        $('.level--zero .wrapper a[href*="#"]').on('click', function () {
          $('.navbar-collapse.mobile--menu--expand').removeClass('show');
          $('header.affix').removeClass('height--fixed');
          $('.menu-button').addClass('collapsed').attr('aria-expanded', 'false');
          var $hash = $(this.hash);
          if (!$('header').hasClass('scrolled--down')) {
            setTimeout(function () {
              $('html,body').animate({scrollTop: ($hash.offset().top - $('header.scrolled--down').outerHeight())}, 500);
            }, 200);
          }
          else {
            $('html,body').animate({scrollTop: ($hash.offset().top - $('header').outerHeight())}, 500);
          }
        });
      }
    };
    Drupal.behaviors.onload = {
      attach: Functions.onload
    };
    Drupal.behaviors.onKeyPress = {
      attach: Functions.onKeyPress
    };
    Drupal.behaviors.scrollheader = {
      attach: Functions.scrollheader
    };
    Drupal.behaviors.mainmenu = {
      attach: Functions.mainmenu
    };
    Drupal.behaviors.embeddClass = {
      attach: Functions.embeddClass
    };
    Drupal.behaviors.headeroverlay = {
      attach: Functions.headeroverlay
    };
    Drupal.behaviors.headerLogo = {
      attach: Functions.headerLogo
    };
    Drupal.behaviors.shareIcon = {
      attach: function () {
        $('.block-title').on('click', function () {
          $('.social-media-sharing ul').toggleClass('d-none');
        });
      }
    };
    Drupal.behaviors.disabledoubletapios = {
      attach: Functions.disabledoubletapios
    };
    Drupal.behaviors.sliderSwipe = {
      attach: Functions.sliderSwipe
    };
    Drupal.behaviors.removeoutline = {
      attach: Functions.removeoutline
    };
  }
})(jQuery, Drupal, window, Functions, drupalSettings);
