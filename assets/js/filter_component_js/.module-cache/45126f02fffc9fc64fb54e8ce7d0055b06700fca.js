jQuery.noConflict();
(function ($) {

  /**
   * FilterClass used to display filters and data on front
   */
  var FilterClass = React.createClass({displayName: "FilterClass",

    /**
     * Set initial state of component
     * @returns {{tags: {}, categories: {}, selected: {}}}
     */
    getInitialState: function () {
      return {
        tags                 : [],
        categories           : [],
        selectedPosts        : [{post_mame: "No items at this time"}],
        usedFiltersCats      : [],
        usedFiltersTags      : [],
        settingsHeadTag      : '',
        settingsHeadClass    : '',
        settingsContentTag   : '',
        settingsContentClass : '',
        load                 : {display: 'none'}
      }
    },

    /**
     * Handle click on add category, transfer it to used filters categories
     *
     * @param item {string}
     *
     * @return void
     */
    handleClickAddCat: function (item) {

      var oldState      = this.state.usedFiltersCats;
      var categoriesOld = this.state.categories;
      this.setState({
        usedFiltersCats: oldState.concat(item),
        categories     : _.without(categoriesOld, item)
      })
      this.forceUpdate(this.changeStateSelected);
    },

    /**
     * Handle remove from used categories, transfer from used to normal categories
     *
     * @param item {string}
     *
     * @return void
     */
    handleClickRemoveCat: function (item) {
      var oldstate      = this.state.usedFiltersCats;
      var categoriesOld = this.state.categories;
      this.setState({
        usedFiltersCats: _.without(oldstate, item),
        categories     : categoriesOld.concat(item)
      });
      this.forceUpdate(this.changeStateSelected);
    },

    /**
     * Add tag to used array, remove from normal state
     *
     * @param item {string}
     *
     * @return void
     */
    handleClickAddTag: function (item) {

      var oldState = this.state.usedFiltersTags;
      var tagsOld  = this.state.tags;
      this.setState({
        usedFiltersTags: oldState.concat(item),
        tags           : _.without(tagsOld, item)
      })
      this.forceUpdate(this.changeStateSelected)
    },

    /**
     * Remove tag from used array, add him to normal state tags
     *
     * @param item {string}
     *
     * @return void
     */
    handleClickRemoveTag: function (item) {
      var oldstate = this.state.usedFiltersTags;
      var tagsOld  = this.state.tags;
      this.setState({
        usedFiltersTags: _.without(oldstate, item),
        tags           : tagsOld.concat(item)
      });
      this.forceUpdate(this.changeStateSelected);
    },

    /**
     * When component is mounted get data and assign to initial objects
     */
    componentDidMount: function () {
      this.getDataFromApi({}).then(function (data) {
        this.setState({
          selectedPosts       : (data.data.selected !== null) ? data.data.selected : [""],
          categories          : (data.data.categories !== null) ? data.data.categories : [""],
          tags                : (data.data.tags !== null) ? data.data.tags : [""],
          settingsHeadTag     : (data.data.settings.heading !== null || data.data.settings.heading != typeof undefined) ? data.data.settings.heading : 'h2',
          settingsHeadClass   : (data.data.settings.heading_class !== null || data.data.settings.heading_class != typeof undefined ) ? data.data.settings.heading_class : 'entry',
          settingsContentTag  : (data.data.settings.content !== null || data.data.settings.content != typeof undefined ) ? data.data.settings.content : 'div',
          settingsContentClass: (data.data.settings.content_class !== null || data.data.settings.content_class != typeof undefined ) ? data.data.settings.content_class : 'entry-content',
          load: {display: 'block'},
        })
      }.bind(this));

    },

    /**
     * Change current state of items by hand of tags and categories
     *
     * @return void
     */
    changeStateSelected: function(){
      var data = { categories: this.state.usedFiltersCats, tags: this.state.usedFiltersTags};
      this.getDataFromApi(data).then(function(data){
        this.setState({
          selectedPosts: (data.data.selected != undefined && data.data.selected !== null) ? data.data.selected : [""]
        })
      }.bind(this))
    },

    /**
     * Send data to back end via Ajax
     * @returns {*}
     */
    getDataFromApi: function (data) {
      return $.get(filter_objects.ajax_url, {action: 'ot_api_data', page_id: filter_objects.page_id , params: data});
    },

    /**
     * Render component
     * @returns {XML}
     */
    render: function () {
      var selected  = this.state.selectedPosts,
      categories    = this.state.categories,
      tags          = this.state.tags,
      usedCats      = this.state.usedFiltersCats,
      usedTags      = this.state.usedFiltersTags,
      contentTag    = "<" + this.state.settingsContentTag + " className='"+ this.state.settingsHeadClass +"'>",
      contentClose  = '</' + this.state.settingsContentTag + '>',
      headTag       = '<' + this.state.settingsHeadTag + ' className="' + this.state.settingsHeadClass + '">',
      headCloseTag  = '</' + this.state.settingsHeadClass + '>',
      style = this.state.load;
      return (
        React.createElement("div", {style: style}, 
          React.createElement("div", {className: "ot_categories"}, 
            React.createElement("h3", null, filter_objects.title_cat), 
              categories.map(function (cat) {
                return React.createElement(Category, {onClick: this.handleClickAddCat.bind(null, cat), categoryName: cat})
              }.bind(this))
          ), 
          React.createElement("div", {className: "ot_tags"}, 
            React.createElement("h3", null, filter_objects.title_tag), 
              tags.map(function (tag) {
                return React.createElement(Tag, {onClick: this.handleClickAddTag.bind(null, tag), tagName: tag})
              }.bind(this))
          ), 
          React.createElement("div", {className: "ot_used_filters_cats"}, 
            React.createElement("h3", null, filter_objects.used_filters), 
              usedCats.map(function (usedFilter) {
                return React.createElement(UsedFilterCats, {onClick: this.handleClickRemoveCat.bind(null, usedFilter), 
                                       usedFilter: usedFilter})
              }.bind(this))
          ), 
          React.createElement("div", {className: "ot_used_filters_tags"}, 
              usedTags.map(function (usedFilter) {
                return React.createElement(UsedFilterTags, {onClick: this.handleClickRemoveTag.bind(null, usedFilter), 
                                       usedFilter: usedFilter})
              }.bind(this))
          ), 
          React.createElement("div", {className: "row"}, 
            selected.map(function (item) {
              return React.createElement("div", {className: "col-md-3", dangerouslySetInnerHTML: {__html: headTag + item.post_name + headCloseTag + item.thumbnail}})
            })
          )
        )
      );
    }
  });

  /**
   * Category component used to handle categories
   */
  var Category = React.createClass({displayName: "Category",
    render: function () {
      return React.createElement("button", {className: "btn btn-primary", onClick: this.props.onClick}, this.props.categoryName)
    }
  });

  /**
   * Tag component used to handle tags
   */
  var Tag = React.createClass({displayName: "Tag",
    render: function () {
      return React.createElement("button", {className: "btn btn-primary", onClick: this.props.onClick}, this.props.tagName)
    }
  });

  /**
   * UsedFilter component used to handle list of used filters
   */
  var UsedFilterCats = React.createClass({displayName: "UsedFilterCats",
    render: function () {
      return React.createElement("button", {className: "btn btn-primary", onClick: this.props.onClick}, this.props.usedFilter)
    }
  });

  /**
   * Used tags filters component
   */
  var UsedFilterTags = React.createClass({displayName: "UsedFilterTags",
    render: function () {
      return React.createElement("button", {className: "btn btn-primary", onClick: this.props.onClick}, this.props.usedFilter)
    }
  });
  React.render(
    React.createElement(FilterClass, null),
    document.getElementById('ot_filters_display')
  );
})(jQuery);