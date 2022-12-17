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
      else if ('theme' in localStorage) {
        var dark = localStorage.theme === 'true';

        if (settings.d8_night !== dark) {
          check(localStorage.theme);
        }
      }

      $('#dark-mode-switch', context).on('change', function () {
        check($(this).is(':checked'));
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
