<style scoped>

</style>

<template>
    <div>
        <pre>{{ {site} }}</pre>
    </div>
</template>

<script>
    export default {
        props: ['siteId', 'serverId'],
        data() {
            return {
                site: {}
            }
        },
        methods: {
            getSite: async function () {
                let {data: site} = await Nova.request().get('/nova-vendor/nova-forge/server/' + this.serverId + '/site/' + this.siteId)

                this.site = site;

                localStorage.setItem('site.' + this.siteId, JSON.stringify(site))
            }
        },
        mounted() {
            let site = localStorage.getItem('site.' + this.siteId, null);

            if (site !== null) {
                this.site = JSON.parse(site);
            }

            this.getSite();
        }
    }
</script>
