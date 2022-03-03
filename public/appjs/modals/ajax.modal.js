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
      function clickHandler () {
        $(selector).load(modalBodyHref, function (data) {
          if(config['submit'] === true){
            const $form = $($(modalAnchor).find('form').first());
            let time = (new Date()).getTime();
            const id = `id_${time}`;
            $form.prop('id', id)
            $(`#${id}`).ajaxFormModal({
              'parent': modalAnchor,
              'eventListener': config['eventListener']
            })
          }
          $(modalAnchor).modal('show');
          config['eventListener'](modalAnchor);
        })
      }
      $(this).off('click')
      $(this).bind('click', clickHandler)

    })
  };
  $.fn.ajaxFormModal = function (_config) {
    const config = $.extend({
      'eventListener':()=>{},
      'parent': ''
    }, _config)
    return this.each(function () {
      let action = $(this).prop('action')
      let id = $(this).prop('id')
      const submitHandler = (e) => {
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
      };
      $(`#${id}`).off('submit', submitHandler);
      $(`#${id}`).bind('submit', submitHandler )
    })
  };
})($);
