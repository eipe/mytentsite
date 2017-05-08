<template>
    <div>
        <section class="section" v-if="this.$auth.check('admin')">
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
                        <transition enter-active-class="animated fadeIn">
                            <keep-alive>
                                <router-view></router-view>
                            </keep-alive>
                        </transition>
                    </div>
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
