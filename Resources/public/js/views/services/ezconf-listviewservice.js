YUI.add('ezconf-listviewservice', function (Y) {
    Y.namespace('eZConf');

    Y.eZConf.ListViewService = Y.Base.create('ezconfListViewService', Y.eZ.ServerSideViewService, [], {
        initializer: function () {
            console.log("Hey, I'm the ListViewService");

            // we catch the `navigateTo` event no matter from where it comes
            // when bubbling, the event is prefixed with the name of the
            // component which fired the event first.
            // so in this case we could also write
            // this.on('ezconflistview:navigateTo', function (e) {});
            // `e` is the event facade. It contains various informations about
            // the event and if any the custom data passed to fire().
            this.on('*:navigateTo', function (e) {
                this.get('app').navigateTo(
                    e.routeName,
                    e.routeParams
                );
            });
        },

        // _load is automatically called when the view service is configured for
        // a route. callback should be executed when everything is finished
        _load: function (callback) {
            // the request allows to retrieve the matched parameters
            var offset = this.get('request').params.offset,
                uri;

            if ( !offset ) {
                offset = 0;
            }
            uri = this.get('app').get('apiRoot') + 'list/' + offset;

            Y.io(uri, {
                method: 'GET',
                on: {
                    success: function (tId, response) {
                        this._parseResponse(response);
                        callback(this);
                    },
                    failure: this._handleLoadFailure,
                },
                context: this,
            });
        },
    });
});
