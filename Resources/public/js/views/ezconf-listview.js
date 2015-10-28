YUI.add('ezconf-listview', function (Y) {
    // Good practice: use a custom namespace 'eZConf' here
    Y.namespace('eZConf');

    // Make sure the first parameter of Y.Base.create() (internal name) is unique
    // it is used for 3 things in PlatformUI:
    // * to identify the view if one wants to write a plugin for it
    // * to find the corresponding template
    // * to generate a CSS class on its container to ease styling
    Y.eZConf.ListView = Y.Base.create('ezconfListView', Y.eZ.ServerSideView, [], {
        initializer: function () {
            console.log("Hey, I'm the list view");
            this.containerTemplate = '<div class="ez-view-ezconflistview"/>';
        },
    });
});
