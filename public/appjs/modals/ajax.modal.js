(function ($) {
  $.fn.ajaxAction = function (_config) {
    const config = $.extend({
      'submit':true,
      'eventListener':()=>{},
    }, _config)
    return this.each(function () {
      const modalAnchor = $(this).data('xtarget')
      const modalBodyHref = $(this).data('body')
      const selector = `${modalAnchor} .modal-content`;

      const _self = this;
      $(this).on('click', function () {
        $(selector).load(modalBodyHref, function (data) {
          if(config['submit'] === true){
            const $form = $($(modalAnchor).find('form').first());
            $form.ajaxFormModal({
              'parent': modalAnchor,
              'eventListener': config['eventListener']
            })
          }
          $(modalAnchor).modal('show');
          config['eventListener'](modalAnchor);
        })
      })

    })
  };
  $.fn.ajaxFormModal = function (_config) {
    const config = $.extend({
      'eventListener':()=>{},
      'parent': ''
    }, _config)
    return this.each(function () {
      let action = $(this).prop('action')
      $(this).on('submit', (e) => {
        let formData = new FormData($(this)[0]);
        e.preventDefault();
        $.ajax({
          type: "POST",
          enctype: 'multipart/form-data',
          url: action,
          data: formData,
          processData: false,
          contentType: false,
          cache: false,
          timeout: 800000,
          success:  (data) =>{
            $(this).html($(data).html())
            config['eventListener'](config['parent']);

          },
          error: function (e) {

          }
        });
      })
    })
  };
})($);
