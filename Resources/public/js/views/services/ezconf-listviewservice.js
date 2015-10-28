YUI.add('ezconf-listviewservice', function (Y) {
    Y.namespace('eZConf');

    Y.eZConf.ListViewService = Y.Base.create('ezconfListViewService', Y.eZ.ViewService, [], {
        initializer: function () {
            console.log("Hey, I'm the ListViewService");
        },

        // _load is automatically called when the view service is configured for
        // a route. callback should be executed when everything is finished
        _load: function (callback) {
            var capi = this.get('capi'), // REST API JavaScript client
                contentService = capi.getContentService(),
                query = contentService.newViewCreateStruct('ezconf-list', 'LocationQuery');

            // searching for "everything"
            query.body.ViewInput.LocationQuery.Criteria = {SubtreeCriterion: "/1/"};
            contentService.createView(query, Y.bind(function (err, response) {
                // parsing the response and storing the location list
                var locations;

                locations = Y.Array.map(response.document.View.Result.searchHits.searchHit, function (hit) {
                    var loc = new Y.eZ.Location({id: hit.value.Location._href});

                    loc.loadFromHash(hit.value.Location);
                    return loc;
                });
                this.set('locations', locations);
                callback();
            }, this));
        },
    }, {
        ATTRS: {
            locations: {
                value: [],
            }
        }
    });
});
