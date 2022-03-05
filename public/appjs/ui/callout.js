(function ($) {
  $.fn.calloutList = function (config) {
    const _config = $.extend({
      'callouts': [
        {
          'title': 'xxxxxxxxxxx',
          'body': 'xxxxxxxxxxxxx'
        }
      ],
    }, config)
    function init() {
      const renderTemplate = function (title, body) {
        return `
              <div class="callout callout-info">
                  <h5>${title}</h5>
                  <p>${body}</p>
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
              </div>`
      }
      $(this).html('')
      _config.callouts.forEach((callout)=>{
        let $callout = $(renderTemplate(callout.title, callout.body))
        $callout.appendTo($(this));
      })
    }
    return this.each(init)
  }
})(jQuery)
