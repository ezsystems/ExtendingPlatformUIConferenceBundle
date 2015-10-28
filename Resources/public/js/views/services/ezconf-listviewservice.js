YUI.add('ezconf-listviewservice', function (Y) {
    Y.namespace('eZConf');

    Y.eZConf.ListViewService = Y.Base.create('ezconfListViewService', Y.eZ.ViewService, [], {
        initializer: function () {
            console.log("Hey, I'm the ListViewService");
        },
    });
});
