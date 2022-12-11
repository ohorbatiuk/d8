(function ($, Drupal) {
  Drupal.behaviors.D8Night = {
    attach: function attach(context) {
      $('#dark-mode-switch', context).on('change', function () {
        window.location = '/night/' + ($(this).is(':checked') + 0);
      });
    }
  };
})(jQuery, Drupal);
