Nova.$on('servers:request', async function () {
    console.log('Updating server list')
    try {
        let {data: servers} = await Nova.request().get('/nova-vendor/nova-forge/servers')
        Nova.$emit('servers:update', servers);

    } catch (e) {
        Nova.$emit('servers:update-fail', e);
    }
})

Nova.booting((Vue, router) => {
    Vue.config.devtools = true

    Vue.component('server-list', require('./components/ServerList'))

    router.addRoutes([
        {
            name: 'nova-forge',
            path: '/nova-forge',
            component: require('./components/Tool'),
        },
        {
            name: 'server-detail',
            path: '/nova-forge/server/:serverId',
            component: require('./views/Detail'),
            props: true
        },
        {
            name: 'site-detail',
            path: '/nova-forge/server/:serverId/site/:siteId',
            component: require('./views/SiteDetail'),
            props: true
        }
    ])
})
