YUI.add('ezconf-navigationplugin', function (Y) {
    // Good practices:
    // * use a custom namespace 'eZConf' here
    // * put the plugins in a 'Plugin' sub namespace
    Y.namespace('eZConf.Plugin');

    // view service plugins must extend Y.eZ.Plugin.ViewServiceBase
    // Y.eZ.Plugin.ViewServiceBase provides several method allowing to deeply
    // hook into the view service behaviour
    Y.eZConf.Plugin.NavigationPlugin = Y.Base.create('ezconfNavigationPlugin', Y.eZ.Plugin.ViewServiceBase, [], {
        initializer: function () {
            var service = this.get('host'); // the plugged object is called host

            console.log("Hey, I'm a plugin for NavigationHubViewService");
            console.log("And I'm plugged in ", service);
        },
    }, {
        NS: 'ezconfNavigation'
    });

    // registering the plugin for the service
    // with that, the plugin is automatically instanciated and plugged in
    // 'navigationHubViewService' component.
    Y.eZ.PluginRegistry.registerPlugin(
        Y.eZConf.Plugin.NavigationPlugin, ['navigationHubViewService']
    );
});
