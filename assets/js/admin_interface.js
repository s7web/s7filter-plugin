jQuery.noConflict();
(function ($) {
  "use strict";
  /**
   * Container for pages on pages settings tab
   * @type {*|HTMLElement}
   */
  var pagesContainer = $('#ot_pages_list_container').accordion({
      collapsible: true,
      active: false
  });
  $(document).ready(function () {

    if (ot_interface.is_pages) {
      // If page is pages settings fetch saved settings for pages
      //ot.parsePages();
    }

    /**
     * Slider control js for input slider on general settings ( posts per page input )
     */
    $("#ot_posts_per_page_div").slider({
      range: "min",
      value: $("#ot_posts_per_page").val(),
      min: 5,
      max: 100,
      slide: function (event, ui) {
        $("#ot_posts_per_page").val(ui.value);
        $("#ot_number_current").html(ui.value);
      }
    });
  });

  /**
   * Add new page ( Send Ajax Request to backend with page_id ), after save, fetch new data for pages
   * Also this is point where validation of input data is performed
   * Look in Plugin.php for localized error messages
   */
  $('#ot_add_page_button').on('click', function () {
    var page_id = $('#ot_add_new_page').val();
    try {
      if (page_id == '') {
        throw ot_interface.empty_name
      }
      if (isNaN(page_id)) {
        throw ot_interface.less_name
      }
      ot.pagesSave({action: "ot_save_option_pages", page_id: page_id}).then(function () {
        ot.parsePages();
      });
    }
    catch (e) {
      $('#ot_pages_validation').html(e);
    }
  });

  /**
   * Auto complete function for choosing page, min chars to start auto complete is 3
   */
  $('#ot_add_new_page').autocomplete({
    source: ajaxurl + '?' + 'action=ot_get_all_pages_autocomplete',
    minLength: 3
  });

  /**
   * Utility class for plugin
   * @type {}
   */
  var ot = {};

  /**
   * Get all pages from settings action, send Ajax request to backend, return promise
   *
   * @returns {*|.promise}
   */
  ot.getAllPages = function () {
    return $.get(ajaxurl, {"action": "ot_get_all_pages"});
  };

  /**
   * Send post request to backend, in order to save settings data
   *
   * @param data {object} example {action: "your_action", "data": "your_data"}
   *
   * @returns {*|$.promise}
   */
  ot.pagesSave = function (data) {
    return $.post(ajaxurl, data);
  };

  /**
   * Parse settings pages and display them in UI
   *
   * @return void
   */
  ot.parsePages = function () {
    this.getAllPages().then(function (data) {
      if (data == false) {
        pagesContainer.html("<h3>" + ot_interface.no_pages + "</h3>");
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
