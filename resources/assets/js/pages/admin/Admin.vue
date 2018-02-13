<template>
    <div class="page page-allow-overflow" v-if="this.$auth.check('admin')">
        <nav class="nav white">
            <div class="nav-center">
                <div class="nav-item"><router-link to="/admin/dashboard">Dashboard</router-link></div>
                <div class="nav-item"><router-link to="/admin/tentsites">Tent sites</router-link></div>
                <div class="nav-item"><router-link to="/admin/users">Users</router-link></div>
                <div class="nav-item"><router-link to="/admin/statistics">Statistics</router-link></div>
            </div>
        </nav>
        <section class="hero content">
            <div class="hero-body">
                <div class="content container">
                    <transition enter-active-class="animated fadeIn">
                        <keep-alive>
                            <router-view></router-view>
                        </keep-alive>
                    </transition>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        name: "Admin",
        data() {
            return {
            }
        },
        components: {
        },
        activated() {
            if(!this.$auth.check("admin")) {
                this.$store.dispatch(
                    "displayError",
                    "You don\'t have access to this page and was therefore redirected back to the main page"
                );
                Vue.router.push({
                    path: "/"
                });
            }
        }
    }
</script>
