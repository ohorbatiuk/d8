/**
 * @file
 * Activates tooltips for tags with a title attribute.
 */

((Drupal) => {

  /**
   * Includes special theming for titles.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Applies Bootstrap appearance for titles.
   */
  Drupal.behaviors.d8ThemeTooltip = {
    attach(context) {
      const tooltips = context.querySelectorAll('[title]');
      [...tooltips].map(tooltip => new bootstrap.Tooltip(tooltip));
    },
  };

})(Drupal);
