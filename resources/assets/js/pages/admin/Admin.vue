<template>
    <div class="page page-allow-overflow">
        <section class="section">
            <div class="container">
                <div class="columns">
                    <div class="column is-narrow">
                        <aside class="menu">
                            <p class="menu-label">Admin</p>
                            <ul class="menu-list">
                                <li><router-link to="/admin/dashboard">Dashboard</router-link></li>
                                <li><router-link to="/admin/tentsites">Tent sites</router-link></li>
                                <li><router-link to="/admin/users">Users</router-link></li>
                                <li><router-link to="/admin/statistics">Statistics</router-link></li>
                            </ul>
                        </aside>
                    </div>
                    <div class="column content">
                        <keep-alive>
                            <router-view></router-view>
                        </keep-alive>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        data() {
            return {
            }
        },
        components: {
        }
    }
</script>
