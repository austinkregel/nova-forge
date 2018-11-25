<style scoped>

</style>

<template>
    <div>
        <loading-view :loading="sites.length === 0">
        <loading-card :loading="sites.length === 0" class="card relative">
            <table
                    v-if="sites.length > 0"
                    class="table w-full"
                    cellpadding="0"
                    cellspacing="0"
                    data-testid="resource-table"
            >
                <thead>
                <tr>
                    <th class="text-left">
                          <span class="inline-flex items-center">
                              Name
                          </span>
                    </th>
                    <th class="text-left">
                          <span class="inline-flex items-center">
                             Repository
                          </span>
                    </th>
                    <th class="text-left">
                          <span class="inline-flex items-center">
                             Directory
                          </span>
                    </th>
                    <th class="text-left">
                          <span class="inline-flex items-center">
                             Created
                          </span>
                    </th>
                    <th class="text-left" v-if="anyTags">
                          <span class="inline-flex items-center">
                             Tags
                          </span>
                    </th>
                    <th>&nbsp;<!-- View --></th>
                </tr>
                </thead>
                <tbody v-for="site in sites">
                <tr>
                    <td>
                        {{ site.name }}
                    </td>
                    <td>{{ site.repository }}</td>
                    <td>{{ site.directory || '/' }}</td>
                    <td>{{ site.human_created }}</td>
                    <td v-if="anyTags">
                        <span v-for="tag in site.tags" class="border border-50 rounded text-sm bg-30 py-1 px-2">{{ tag.name }}</span>
                    </td>
                    <td>
                        <span>
                            <router-link
                                    class="cursor-pointer text-70 hover:text-primary mr-3"
                                    :to="{ name: 'site-detail', params: {
                                    siteId: site.id,
                                    serverId: server.id
                                }}"
                                :title="__('View')"
                            >
                                <icon type="view" width="22" height="18" view-box="0 0 22 16"/>
                            </router-link>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
            <pre>{{ server }}</pre>
        </loading-card>
        </loading-view>
    </div>
</template>

<script>
    export default {
        props: ['serverId'],
        data() {
            return {
                server: {}
            }
        },
        watch: {
            serverId() {
                this.getServer();
            }
        },
        computed: {
            sites() {
                return this.server.sites || [];
            },
            firewall() {
                return this.server.firewall || [];
            },
            jobs() {
                return this.server.jobs || [];
            },
            daemons() {
                return this.server.daemons || []
            },
            anyTags() {
                if (this.sites.length === 0) {
                    return false
                }

                return this.sites.map(site => site.tags.length).reduce((agg, base) => (agg += base), 0) > 0;
            }
        },
        methods: {
            getServer: async function () {
                let {data: server} = await Nova.request().get('/nova-vendor/nova-forge/servers/' + this.serverId)

                this.server = server;
            }
        },
        mounted() {
            this.getServer()
        }
    }
</script>
