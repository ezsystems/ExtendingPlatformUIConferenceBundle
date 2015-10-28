YUI.add('ezconf-listview', function (Y) {
    // Good practice: use a custom namespace 'eZConf' here
    Y.namespace('eZConf');

    // Make sure the first parameter of Y.Base.create() (internal name) is unique
    // it is used for 3 things in PlatformUI:
    // * to identify the view if one wants to write a plugin for it
    // * to find the corresponding template
    // * to generate a CSS class on its container to ease styling
    Y.eZConf.ListView = Y.Base.create('ezconfListView', Y.eZ.TemplateBasedView, [], {
        initializer: function () {
            console.log("Hey, I'm the list view");
        },

        render: function () {
            // this.get('container') is an auto generated <div>
            // here, it's not yet in the DOM of the page and it will be added
            // after the execution of render().

            // when extending Y.eZ.TemplateBasedView
            // this.template is the result of the template
            // compilation, it's a function. You can pass an object
            // in parameters and each property will be available in the template
            // as a variable named after the property.
            this.get('container').setHTML(
                this.template({
                    locations: this._getJsonifiedLocations(),
                })
            );
            return this;
        },

        _getJsonifiedLocations: function () {
            // to get usable objects in the template
            return Y.Array.map(this.get('locations'), function (loc) {
                return loc.toJSON();
            });
        },
    }, {
        ATTRS: {
            locations: {
                value: [],
            }
        }
    });
});
