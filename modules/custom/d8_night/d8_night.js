(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.d8Night = {
    attach: function attach(context, settings) {
      function check(night) {
        $.ajax({
          url: Drupal.url('night/' + (night ? 1 : 0)),
          dataType: 'json',
          success: function (data) {
            if (data.update) {
              localStorage.reload = true;
              window.location.reload();
            }
          }
        });
      }

      if ('reload' in localStorage) {
        localStorage.removeItem('reload');
      }
      else {
        var night;

        if ('theme' in localStorage) {
          night = localStorage.theme === 'true';
        }
        else {
          night = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        if (settings.d8_night !== night) {
          check(night);
        }
      }

      var $element = $('#dark-mode-switch', context);

      $element
        .val($element.is(':checked') + 0)
        .on('change', function () {
          check($(this).is(':checked'));
        });
    }
  };
})(jQuery, Drupal, drupalSettings);
