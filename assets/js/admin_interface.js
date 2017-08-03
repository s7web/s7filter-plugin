jQuery.noConflict();
(function ($) {
  "use strict";
  /**
   * Container for pages on pages settings tab
   * @type {*|HTMLElement}
   */
  var pagesContainer = $('#s7_pages_list_container').accordion({
      collapsible: true,
      active: false
  });
  $(document).ready(function () {

    if (s7_interface.is_pages) {
      // If page is pages settings fetch saved settings for pages
      //s7.parsePages();
    }

    /**
     * Slider control js for input slider on general settings ( posts per page input )
     */
    $("#s7_posts_per_page_div").slider({
      range: "min",
      value: $("#s7_posts_per_page").val(),
      min: 5,
      max: 100,
      slide: function (event, ui) {
        $("#s7_posts_per_page").val(ui.value);
        $("#s7_number_current").html(ui.value);
      }
    });
  });

  /**
   * Add new page ( Send Ajax Request to backend with page_id ), after save, fetch new data for pages
   * Also this is point where validation of input data is performed
   * Look in Plugin.php for localized error messages
   */
  $('#s7_add_page_button').on('click', function () {
    var page_id = $('#s7_add_new_page').val();
    try {
      if (page_id == '') {
        throw s7_interface.empty_name
      }
      if (isNaN(page_id)) {
        throw s7_interface.less_name
      }
      s7.pagesSave({action: "s7_save_option_pages", page_id: page_id}).then(function () {
        s7.parsePages();
      });
    }
    catch (e) {
      $('#s7_pages_validation').html(e);
    }
  });

  /**
   * Auto complete function for choosing page, min chars to start auto complete is 3
   */
  $('#s7_add_new_page').autocomplete({
    source: ajaxurl + '?' + 'action=s7_get_all_pages_autocomplete',
    minLength: 3
  });

  /**
   * Utility class for plugin
   * @type {}
   */
  var s7 = {};

  /**
   * Get all pages from settings action, send Ajax request to backend, return promise
   *
   * @returns {*|.promise}
   */
  s7.getAllPages = function () {
    return $.get(ajaxurl, {"action": "s7_get_all_pages"});
  };

  /**
   * Send post request to backend, in order to save settings data
   *
   * @param data {object} example {action: "your_action", "data": "your_data"}
   *
   * @returns {*|$.promise}
   */
  s7.pagesSave = function (data) {
    return $.post(ajaxurl, data);
  };

  /**
   * Parse settings pages and display them in UI
   *
   * @return void
   */
  s7.parsePages = function () {
    this.getAllPages().then(function (data) {
      if (data == false) {
        pagesContainer.html("<h3>" + s7_interface.no_pages + "</h3>");
      }else{
        pagesContainer.html("");
        Object.keys(data).map(function(key){
          pagesContainer.append('<h3>' + data[key].title + '</h3>');
          pagesContainer.append($('template').html());
        });
        window.location.reload();
      }
    });
  }
})(jQuery);
