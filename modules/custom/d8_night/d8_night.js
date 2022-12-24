(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.D8Night = {
    attach: function attach(context, settings) {
      function check(mode) {
        $.ajax({
          url: Drupal.url('night/' + (mode ? 1 : 0)),
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
        var dark;

        if ('theme' in localStorage) {
          dark = localStorage.theme === 'true';
        }
        else {
          dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        console.log(dark);
        console.log(settings.d8_night);

        if (settings.d8_night !== dark) {
          check(dark);
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
