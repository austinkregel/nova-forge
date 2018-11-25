<style scoped>

</style>

<template>
    <div>
        <ul class="list-reset mb-8">
            <li class="leading-tight mb-4 ml-8 text-sm" v-for="server in servers">
                <router-link
                        tag="a"
                        :to="{name: 'server-detail', params: {
                            serverId: server.id
                        }}"
                        class="cursor-pointer flex items-center font-normal text-white mb-3 text-base no-underline"
                >
                    {{ server.name }}
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: [],
        data() {
            return {
                servers: []
            }
        },
        methods: {
            getServers: async function() {
                Nova.$emit('servers:request')
            }
        },
        mounted() {
            Nova.$on('servers:update', (servers) => {
                this.servers = servers
            })
            this.getServers();
        }
    }
</script>
