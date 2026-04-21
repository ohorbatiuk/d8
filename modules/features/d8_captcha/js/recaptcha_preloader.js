((window, document, $) => {

  'use strict';

  const query = window.matchMedia('(prefers-color-scheme: dark)');

  window.onLoadReCaptcha = () => {
    document.querySelectorAll('.g-recaptcha').forEach(el => {
      const key = el.dataset.sitekey;
      const themes = {true: 'dark', false: 'light'};
      const dark = el.dataset.theme === themes[true];
      const container = el.cloneNode(false);

      if (query.matches !== dark) {
        const preloader = el.nextElementSibling.classList;

        preloader.remove(el.dataset.theme);
        preloader.add(themes[!dark]);
      }

      el.parentNode.replaceChild(container, el);

      window.grecaptcha.render(container, {
        sitekey: key,
        theme: themes[query.matches],
      });

      const observer = new MutationObserver(() => {
        if (container.querySelector('iframe')) {
          observer.disconnect();

          $(container)
            .closest('.g-recaptcha-wrapper')
            .removeClass('loading')
            .closest('form')
            .find('.blocked-by-recaptcha')
            .removeAttr('disabled')
            .removeClass('blocked-by-recaptcha is-disabled');
        }
      });

      observer.observe(container, {
        childList: true,
        subtree: true,
      });
    });
  }

  query.addEventListener('change', window.onLoadReCaptcha);

})(window, document, jQuery);
