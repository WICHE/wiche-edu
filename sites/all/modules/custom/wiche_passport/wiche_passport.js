(function ($) {
  Drupal.behaviors.wiche_passport = {
    attach: function (context, settings) {

      $("#transferTable", context).each(function () {
        var arg = Drupal.settings.wiche_passport.state;
        wiche_passport_within_onload(arg);
      });
      $("#region-map", context).each(function () {
        wiche_passport_region_onload();
      });
      $("#region-filter", context).click(function () {
        wiche_passport_region_onFilterClick();
      });
      /**
       * Utility function to get a url parameter by name.
       * tested with urls that have parameters in the for of xxx?param_name=yyy
       *
       * @param name
       * @returns value of parameter
       */
      function wiche_passport_getParam(name) {
        var name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.href);
        if (results == null)
          return "";
        else
          return results[1];
      }


      /**
       * Utility function that reads region page filter checkboxes into javascript
       * objects of the form {checkboxname:value}.
       *
       * @returns filter objects
       */
      function wiche_passport_get_region_filter() {
        var filterArray = new Array();
        var nodeList = document.getElementsByTagName('input');

        //load array from nodeList of checkboxes that are checked.
        //var filters = {}; //js object with key as checkbox name, value as true for checked.
        var filters = new Object;

        var index = 0;
        try {
          for (i = 0; i < nodeList.length; i++) {
            //alert(i);
            var node = nodeList.item(i);

            if (node.type == 'checkbox' && node.checked) {
              filters[node.name] = true;
              //alert('node.type' + item.type);
            }
          }
        } catch (err) {
          alert(err);
        }

        return filters;

      };


      /**
       * Reads data for passport within page.
       * Called from passport-cohort-yearly-transfers block.
       *
       * @returns {Boolean}
       */
      function wiche_passport_within_onload(state_id) {

        if (state_id == '') {
          alert('Page requires state_id argument in url.');
        }

        $.ajax({
          type: 'POST',
          url: '/passport/studentTransfers/within-page-load/' + state_id, // Which url should be handle the ajax request
          success: wiche_passport_update_within,
          dataType: 'json' //define the type of data that is going to get back from the server
        });
        return false;  // return false so the navigation stops here and not continue to the page in the link

      };


      /**
       * Client side callback function that will get exceuted after ajax request is completed successfully.
       * Responsible for updating the page's content dynamically with elements stored in the
       * data is an associative array where the key is the divID and value is the data to display.
       *
       */
      function wiche_passport_update_within(data) {
        for (element in data) {
          var value = data[element];
          var elementId = '#' + element;
//alert("elementId: "+elementId);
//alert("value: "+value);
//alert(isNaN(value));

          //format number value adding a comma
          if (!isNaN(value)) {
            value = wiche_passport_addCommas(value);
          }

          //update the div's content
          $(elementId).html(value);
        }
      };


      /**
       * OnLoad Event Handler to read data from the database for the region page when it is loaded.
       * All values are read onload.
       *
       * Called from passport/studentTransfers/region/xx page
       *
       * @returns {Boolean}
       */
      function wiche_passport_region_onload() {
        //alert('start region onload handler');

        //get filter parameters from page
        var filters = wiche_passport_get_region_filter();
        //alert(filters);

        //state_id input from url
        var state_id = wiche_passport_getParam('state_id');
        //alert(state_id);

        if (state_id == '') {
          alert('Page requires state_id argument in url.');
        }


        $.ajax({
          type: 'POST',
          url: '/passport/studentTransfers/region-filter-action/' + state_id, // Which url should be handle the ajax request, a drupal server side module method.
          dataType: 'json', //define the type of data that is going to get back from the server
          success: wiche_passport_update_region, // The js function that will be called upon success request
          data: filters //Pass javascript objects in an array with key/value pairs

        });

        return false;  // return false so the navigation stops here and not continue to the page in the link
      };

      /**
       * Onclick event handler to read data from the database for the region page when the filter button is clicked.
       *
       * Called from passport/studentTransfers/region/xx page
       *
       * @returns {Boolean}
       */
      function wiche_passport_region_onFilterClick() {

        cursor_wait();

        //state_id input from url
        var state_id = wiche_passport_getParam('state_id');
        //alert('start region onClick handler for' + state_id);


        if (state_id == '') {
          alert('Page requires state_id argument in url.');
        }

        //get filter parameters from page
        var filters = wiche_passport_get_region_filter();

        $.ajax({
          type: 'POST',
          url: '/passport/studentTransfers/region-filter-action/' + state_id, // Which url should be handle the ajax request. This is the url defined in the <a> html tag
          success: wiche_passport_update_region, // The js function that will be called upon success request
          dataType: 'json', //define the type of data that is going to get back from the server

          //TODO - load up the data value with all items that are checked.
          data: filters //Pass javascript objects in an array with key/value pairs
        });

        return false;  // return false so the navigation stops here and not continue to the page in the link

      };

      /**
       * Client side callback function that will get exceuted after ajax request is completed successfully.
       * Responsible for updating the page's content dynamically with elements stored in the
       * data json object parameter.
       *
       */
      function wiche_passport_update_region(data) {

        var state_id = data.state_id;
        var state_name = data.state_name;

        if (state_id != null) {

          //set the map image
          $('#region-image').attr('src', '/info/passport/images/map/' + state_id + '.png');

          //hide all elements that are currently visible using a class selector
          $('.show_element').removeClass().addClass('hide_element');

          //set div values
          for (element in data) {

            //TODO - state_id is processed in this loop, add logic to skip it?

            //get element data
            value = data[element];
            elementId = '#' + element;

            //format number value adding a comma
            if (!isNaN(value)) {
              value = wiche_passport_addCommas(value);
            }

            //update the div elements
            $(elementId).html(value).removeClass().addClass('show_element');

            //update the map icon for map elements
            if (wiche_passport_is_map_element(element)) {
              //get icon element id
              iconId = '#' + wiche_passport_get_icon_id(element);

              //alert(iconId);

              //display the mapped value's icon
              $(iconId).removeClass().addClass('show_element');
            }


          }

          //display within icon for the current state in the from icon.
          //database query sets the from field with the within total.
          //we assume the to-icon is visible from the logic executed above that showed it.
          $('#' + state_id + '-from-icon').attr('src', '/info/passport/images/icons/within.png');

          //change the color of the text for within display
          $('#' + state_id + '-from-div').removeClass().addClass('state-within');

          //hides to elements for the within state
          $('#' + state_id + '-to-icon').removeClass().addClass('hide_element');
          $('#' + state_id + '-to-div').removeClass().addClass('hide_element');

          //set state name divs on page
          $('#withinStateDiv').html(state_name);
          $('#toStateDiv').html(state_name);
          $('#fromStateDiv').html(state_name);
          $('#region-title-state').html(state_name);

        } else {
          alert('state parameter required for page');
        }

        cursor_clear();

      };


      /**
       * Helper - Returns true if the element passed in is a map element.
       * Map elements contain the string -from or -to in them.
       */
      function wiche_passport_is_map_element(element) {

        return (element.indexOf('-from') > 0 || element.indexOf('-to') > 0);

      };


      /**
       * Helper - Build the icon id from the input map element div id. Element div ids are in the
       * form of CO-from-div or CO-to-div and icon ids are in the form of CO-from-icon or CO-to-icon.
       *
       * @param String element
       */
      function wiche_passport_get_icon_id(element) {

        return element.replace('div', 'icon');

      };

//Changes the cursor to an hourglass
      function cursor_wait() {
        document.body.style.cursor = 'wait';
      };

// Returns the cursor to the default pointer
      function cursor_clear() {
        document.body.style.cursor = 'default';
      };

      function wiche_passport_addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
      };

      /**
       * Add event handlers to page.
       * Note: this code is executed on all pages on the site.
       MOVED this to the html on the region page. THIS WAS FIRING EVEN THOUGH THE CLICK EVENT DID NOT OCCUR. INVESTIGATE.
       Drupal.behaviors.wiche_passport = function (context) {
		  
	  //handle region-filter click event
	  $('#region-filter', context).click(wiche_passport_region_onFilterClick());
	
};
       */
    }
  };
}(jQuery));