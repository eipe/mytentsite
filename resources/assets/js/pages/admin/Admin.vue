<template>
    <div class="page page-allow-scroll">
        <div class="row">
            <div class="columns medium-centered text-left">
                <h1>Admin</h1>
                <ul>
                    <li><router-link to="/admin/dashboard">Dashboard</router-link></li>
                    <li><router-link to="/admin/tentsites">Tent sites</router-link></li>
                    <li><router-link to="/admin/users">Users</router-link></li>
                    <li><router-link to="/admin/statistics">Statistics</router-link></li>
                </ul>
                <keep-alive>
                    <router-view></router-view>
                </keep-alive>
            </div>
        </div>
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
