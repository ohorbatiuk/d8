/**
 * @file
 * Provides a checkbox/radio view in Bootstrap style.
 */

(($, Drupal, drupalSettings) => {

  /**
   * Includes special theming for checkbox/radio form elements.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Applies Bootstrap appearance for checkboxes/radios.
   */
  Drupal.behaviors.d8ThemeCheckboxOrRadio = {
    attach(context, settings) {
      $('.form-check-input', context).change(function () {
        const type = $(this).attr('type');
        const icons = settings.d8Theme[type];
        const radio = type === 'radio';
        const wrapper = $(this).parent();

        if (radio) {
          wrapper.parent().find(`.${icons[1]}`)
            .removeClass(icons[1])
            .addClass(icons[0]);
        }

        const checked = radio || $(this).is(':checked') ? 1 : 0;

        wrapper.find(`.${icons[1 - checked]}`)
          .removeClass(icons[1 - checked])
          .addClass(icons[checked]);
      });
    },
  };

})(jQuery, Drupal, drupalSettings);
