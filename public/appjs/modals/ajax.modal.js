(function ($) {
  $.fn.ajaxAction = function (_config) {
    const config = $.extend({
      'submit':true
    }, _config)
    return this.each(function () {
      const modalAnchor = $(this).data('xtarget')
      const modalBodyHref = $(this).data('body')
      const selector = `${modalAnchor} .modal-content`;

      const _self = this;
      $(this).on('click', function () {
        $(selector).load(modalBodyHref, function (data) {
          if(config['submit'] === true){
            const formSelector = `${modalAnchor} .modal-content form`;
            $(formSelector).ajaxFormModal({
            })
          }
          $(modalAnchor).modal('show');
        })
      })

    })
  };
  $.fn.ajaxFormModal = function (_config) {
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
          },
          error: function (e) {

          }
        });
      })
    })
  };
})($);
