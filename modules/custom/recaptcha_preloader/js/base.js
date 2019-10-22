const onLoadReCaptcha = function () {
  jQuery('.g-recaptcha iframe').on('load', function () {
    jQuery(this)
      .closest('.g-recaptcha-wrapper')
      .removeClass('loading')
      .closest('form')
      .find('.blocked-by-recaptcha')
      .removeAttr('disabled')
      .removeClass('blocked-by-recaptcha');
  });
};
