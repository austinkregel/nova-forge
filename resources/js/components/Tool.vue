<template>
    <div v-if="false">
        <heading class="mb-6">Nova Forge</heading>

        <div class="flex flex-wrap mt-2" v-for="rows in chunkedServers">
            <div v-for="server in rows" :class="class_width(rows)">
                <div class="shadow-md bg-white m-3 rounded overflow-hidden flex flex-col">
                    <div class="text-sm px-4 pb-3 pt-4 text-grey-darkest text-lg">
                        <div class="flex flex-wrap p-1">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm2-2.25a8 8 0 0 0 4-2.46V9a2 2 0 0 1-2-2V3.07a7.95 7.95 0 0 0-3-1V3a2 2 0 0 1-2 2v1a2 2 0 0 1-2 2v2h3a2 2 0 0 1 2 2v5.75zm-4 0V15a2 2 0 0 1-2-2v-1h-.5A1.5 1.5 0 0 1 4 10.5V8H2.25A8.01 8.01 0 0 0 8 17.75z"/>
                            </svg>
                            <div class="flex-1 pl-1">{{ server.region }}</div>
                        </div>
                        <div class="flex flex-wrap p-1">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 20S3 10.87 3 7a7 7 0 1 1 14 0c0 3.87-7 13-7 13zm0-11a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            </svg>
                            <div class="flex-1 pl-1">{{ server.ip_address }}</div>
                        </div>
                        <div class="flex flex-wrap p-1" v-if="server.network.length > 0">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm7.75-8a8.01 8.01 0 0 0 0-4h-3.82a28.81 28.81 0 0 1 0 4h3.82zm-.82 2h-3.22a14.44 14.44 0 0 1-.95 3.51A8.03 8.03 0 0 0 16.93 14zm-8.85-2h3.84a24.61 24.61 0 0 0 0-4H8.08a24.61 24.61 0 0 0 0 4zm.25 2c.41 2.4 1.13 4 1.67 4s1.26-1.6 1.67-4H8.33zm-6.08-2h3.82a28.81 28.81 0 0 1 0-4H2.25a8.01 8.01 0 0 0 0 4zm.82 2a8.03 8.03 0 0 0 4.17 3.51c-.42-.96-.74-2.16-.95-3.51H3.07zm13.86-8a8.03 8.03 0 0 0-4.17-3.51c.42.96.74 2.16.95 3.51h3.22zm-8.6 0h3.34c-.41-2.4-1.13-4-1.67-4S8.74 3.6 8.33 6zM3.07 6h3.22c.2-1.35.53-2.55.95-3.51A8.03 8.03 0 0 0 3.07 6z"/>
                            </svg>
                            <div class="flex-1 pl-1">{{ connectedNetworks(server).join(', ') }}</div>
                        </div>
                        <div class="flex flex-wrap p-1" v-if="server.tags.length > 0">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M0 10V2l2-2h8l10 10-10 10L0 10zm4.5-4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                            </svg>
                            <div class="flex-1 pl-1">{{ server.tags.join(', ') }}</div>
                        </div>
                    </div>
                    <div class="flex border-t border-grey-lighter p-4">
                        <div class="flex-grow">
                            {{ server.name }}
                        </div>
                        <div>{{ server.size }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div v-else-if="authenticated">
        <loading-view :loading="initialLoading">
            <loading-card :loading="loading" class="card relative">
                <table
                        v-if="servers.length > 0"
                        class="table w-full"
                        cellpadding="0"
                        cellspacing="0"
                        data-testid="resource-table"
                >
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-left">
                          <span class="inline-flex items-center">
                          </span>
                        </th>
                        <th class="text-left">
                          <span class="inline-flex items-center">
                             IP Address
                          </span>
                        </th>
                        <th class="text-left">
                          <span class="inline-flex items-center">
                             Private IP Address
                          </span>
                        </th>
                        <th class="text-left">
                          <span class="inline-flex items-center">
                             Created
                          </span>
                        </th>
                        <th class="text-left">
                          <span class="inline-flex items-center">
                             tags
                          </span>
                        </th>
                        <th>&nbsp;<!-- View --></th>
                    </tr>
                    </thead>
                    <tbody v-for="server in servers">
                    <tr>
                        <td class="w-8">
                            <svg v-if="!server.is_ready || server.revoked" class="w-6 h-6 text-red fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 10a10 10 0 1 1 20 0 10 10 0 0 1-20 0zm16.32-4.9L5.09 16.31A8 8 0 0 0 16.32 5.09zm-1.41-1.42A8 8 0 0 0 3.68 14.91L14.91 3.68z"/></svg>
                            <svg v-if="server.is_ready && !server.revoked" class="w-6 h-6 text-green fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/></svg>
                        </td>
                        <td class="flex flex-wrap items-center content-center">
                            <div class="w-full">
                                {{ server.name }}
                            </div>
                            <div class="text-xs">
                                {{ server.size }} / {{ server.region }}
                            </div>
                        </td>
                        <td>{{ server.ip_address }}</td>
                        <td>{{ server.private_ip_address }}</td>
                        <td>{{ server.human_created }}</td>
                        <td>
                            <span v-for="tag in server.tags" class="border border-50 rounded text-sm bg-30 py-1 px-2">{{ tag.name }}</span>
                        </td>
                        <td>
                            <span>
                                <router-link
                                        class="cursor-pointer text-70 hover:text-primary mr-3"
                                        :to="{ name: 'server-detail', params: {
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
            </loading-card>
        </loading-view>

    </div>
    <card class="px-3 py-3" v-else>
        <h2 class="pb-4">We need your Api Key!</h2>
        <input v-model="api_key" type="text" class="w-full form-control form-input form-input-bordered">
        <div class="flex flex-wrap w-full mt-4">
            <div class="flex-grow"></div>
            <button class="ml-auto btn btn-default btn-primary" @click.prevent="authenticate">
                Save
            </button>
        </div>
    </card>
</template>

<script>
    const array_chunk = (array, chunk_size) => Array(Math.ceil(array.length / chunk_size)).fill().map((_, index) => index * chunk_size).map(begin => array.slice(begin, begin + chunk_size));
    export default {
        data() {
            return {
                authenticated: true,
                initialLoading: true,
                loading: true,
                api_key: '',
                servers: [],
                chunkedServers: []
            }
        },
        methods: {
            connectedNetworks(server) {
                return server.network.map(serverId => {
                    return this.servers.filter(s => (s.id === serverId)).map(s => s.name)[0];
                })
            },
            getServers: async function () {
                this.initialLoading = true;
                this.loading = true;
                Nova.$emit('servers:request');
            },
            authenticate: async function () {
                try {
                    await Nova.request().post('/nova-vendor/nova-forge/authenticate', {
                        forge_key: this.api_key
                    });
                    this.authenticated = true;
                    this.$toasted.show('Successfully authenticatead!', {type: 'success'})
                    this.getServers();
                } catch (e) {
                    this.authenticated = false

                    this.initialLoading = true;
                    this.loading = true
                    this.$toasted.show('Failed to authenticate! Double check your API key.', {type: 'error'})
                }
            },
            class_width(row) {
                let count = row.length;
                if (count >= 4) {
                    return 'w-full md:w-1/2 lg:w-1/4 xl:w-1/4';
                } else if (count === 3) {
                    return 'w-full md:w-1/2 lg:w-1/3 xl:w-1/3';
                } else if (count === 2) {
                    return 'w-full md:w-1/2 lg:w-1/2 xl:w-1/2';
                }
                return 'w-full';
            },
            moment: moment
        },
        mounted() {
            console.log(this.$store)
            this.getServers();
            Nova.$on('servers:update', (servers) => {
                this.servers = servers;
                this.chunkedServers = array_chunk(servers, 4);
                this.authenticated = true;

                this.initialLoading = false;
                this.loading = false;
            })
            Nova.$on('servers:update-fail', (e) => {
                if (e.response.status === 422) {
                    this.authenticated = false;
                }
            })
        },
    }
</script>

<style>
    /* Scoped Styles */
</style>
