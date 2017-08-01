jQuery.noConflict();
(function ($) {

  /**
   * FilterClass used to display filters and data on front
   */
  var FilterClass = React.createClass({

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
        load                 : {display: 'none'},
        current_page         : 1
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
      });
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
      });
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
      this.getDataFromApi({current_page: this.state.current_page}).then(function (data) {
        this.setState({
          selectedPosts       : (data.data.selected !== null) ? data.data.selected : [""],
          categories          : (data.data.categories !== null) ? data.data.categories : [""],
          tags                : (data.data.tags !== null) ? data.data.tags : [""],
          settingsHeadTag     : (data.data.settings.heading !== null && data.data.settings.heading != typeof undefined && data.data.settings.heading != '') ? data.data.settings.heading : 'h2',
          settingsHeadClass   : (data.data.settings.heading_class !== null && data.data.settings.heading_class != typeof undefined && data.data.settings.heading_class != '' ) ? data.data.settings.heading_class : 'entry',
          settingsContentTag  : (data.data.settings.content !== null && data.data.settings.content != typeof undefined && data.data.settings.content != '' ) ? data.data.settings.content : 'div',
          settingsContentClass: (data.data.settings.content_class !== null && data.data.settings.content_class != typeof undefined && data.data.settings.content_class != '') ? data.data.settings.content_class : 'entry-content',
          load: {display: 'block'},
        });
      }.bind(this));

    },

    /**
     * Change current state of items by hand of tags and categories
     *
     * @return void
     */
    changeStateSelected: function(){
      var data = { categories: this.state.usedFiltersCats, tags: this.state.usedFiltersTags, current_page: this.state.current_page};
      this.getDataFromApi(data).then(function(data){
        this.setState({
          selectedPosts: (data.data.selected != undefined && data.data.selected !== null) ? data.data.selected : [""]
        })
      }.bind(this))
    },

    callPage: function(page) {
      if(page < 1){
        return;
      }
      this.setState({
        current_page: page
      });
      this.forceUpdate(this.changeStateSelected);
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
      headTag       = '<' + this.state.settingsHeadTag + ' className="' + this.state.settingsHeadClass + '">',
      headCloseTag  = '</' + this.state.settingsHeadTag + '>',
      current_page  = this.state.current_page,
      nextPrevious = "",
      style = this.state.load;
      if (usedCats.length < 1 && usedTags.length < 1) {
        nextPrevious = <div className="ot_pagination_buttons"><PreviousPageButton onClick={this.callPage.bind(null, current_page - 1)}></PreviousPageButton><NextPageButton onClick={this.callPage.bind(null, current_page + 1)}></NextPageButton></div>;
      }
      return (
        <div style={style}>
          <div className="ot_categories">
            <h3>{filter_objects.title_cat}</h3>
              {categories.map(function (cat) {
                var catElement = '';
                if(cat.length > 0){
                  catElement = <Category onClick={this.handleClickAddCat.bind(null, cat)} categoryName={cat}/>
                }
                return catElement
              }.bind(this))}
          </div>
          <div className="ot_tags">
            <h3>{filter_objects.title_tag}</h3>
              {tags.map(function (tag) {
                var tagElement = '';
                if(tag.length > 0){
                  tagElement = <Tag onClick={this.handleClickAddTag.bind(null, tag)} tagName={tag}/>
                }
                return tagElement
              }.bind(this))}
          </div>
          <div className="ot_used_filters_cats">
            <h3>{filter_objects.used_filters}</h3>
              {usedCats.map(function (usedFilter) {
                return <UsedFilterCats onClick={this.handleClickRemoveCat.bind(null, usedFilter)}
                                       usedFilter={usedFilter}/>
              }.bind(this))}
          </div>
          <div className="ot_used_filters_tags">
              {usedTags.map(function (usedFilter) {
                return <UsedFilterTags onClick={this.handleClickRemoveTag.bind(null, usedFilter)}
                                       usedFilter={usedFilter}/>
              }.bind(this))}
          </div>
          <div className="row">
            {selected.map(function (item) {
              return <div className="col-md-3" dangerouslySetInnerHTML={{__html: headTag + item.post_name + headCloseTag + '<div class="row">' + item.thumbnail + '</div>'}} ></div>
            })}
          </div>
          <div className="row">
            {nextPrevious}
          </div>
        </div>
      );
    }
  });

  /**
   * Category component used to handle categories
   */
  var Category = React.createClass({
    render: function () {
      return <button className="btn btn-blue ot_space_filter_buttons" onClick={this.props.onClick}>{this.props.categoryName}</button>
    }
  });

  /**
   * Tag component used to handle tags
   */
  var Tag = React.createClass({
    render: function () {
      return <button className="btn btn-blue ot_space_filter_buttons" onClick={this.props.onClick}>{this.props.tagName}</button>
    }
  });

  /**
   * UsedFilter component used to handle list of used filters
   */
  var UsedFilterCats = React.createClass({
    render: function () {
      return <button className="btn btn-blue ot_space_filter_buttons" onClick={this.props.onClick}>{this.props.usedFilter}</button>
    }
  });

  /**
   * Used tags filters component
   */
  var UsedFilterTags = React.createClass({
    render: function () {
      return <button className="btn btn-blue ot_space_filter_buttons" onClick={this.props.onClick}>{this.props.usedFilter}</button>
    }
  });

  var NextPageButton = React.createClass({
    render: function(){
      return <button className="btn btn-blue ot_space_filter_buttons" onClick={this.props.onClick}> Next </button>
    }
  });
  var PreviousPageButton = React.createClass({
    render: function(){
      return <button className="btn btn-blue ot_space_filter_buttons" onClick={this.props.onClick}> Previous </button>
    }
  });
  React.render(
    <FilterClass />,
    document.getElementById('ot_filters_display')
  );
})(jQuery);