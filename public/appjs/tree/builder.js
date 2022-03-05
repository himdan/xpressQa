(function ($) {
  $.fn.treeBuilder = function (config = {}) {
    this.each(function () {
      const _self = this;

      function build($ul) {
        return (item) => {
          const $li = $('<li></li>').text(item.name)
          if(item.data){
            $li.data(item.data);
          }
          if (item.children) {
            const $ul2 = $('<ul></ul>')
            item.children.forEach(function (child) {
              build($ul2)(child)
            })
            $ul2.appendTo($li)
          }
          $li.appendTo($ul)
        }
      }

      const success = config.ajax.success(function (data) {
        const $ul = $('<ul></ul>')
        data.providers.forEach(build($ul))
        $ul.appendTo($(_self))
        $(_self).jstree({
          "checkbox": {
            "keep_selected_style": false
          },
          "plugins": ["checkbox", 'changed']
        });

      })
      $.ajax({
        url: config.ajax.url,
        success: success
      })
    })
  };
})(jQuery)
