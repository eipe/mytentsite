<template>
    <div class="" v-if="this.$auth.check('admin')">
        <section class="hero is-dark">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">Administrator's homeground</h1>
                    <h2 class="subtitle">Make sure that the tent site is up and running!</h2>
                </div>
            </div>
            <div class="hero-foot">
                <div class="container">
                    <div class="tabs is-boxed">
                        <ul>
                            <router-link to="/admin/dashboard" tag="li"><a>Dashboard</a></router-link>
                            <router-link to="/admin/tentsites" tag="li"><a>Tent sites</a></router-link>
                            <router-link to="/admin/users" tag="li"><a>Users</a></router-link>
                            <router-link to="/admin/statistics" tag="li"><a>Statistics</a></router-link>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container content">
                <transition enter-active-class="animated fadeIn">
                    <keep-alive>
                        <router-view></router-view>
                    </keep-alive>
                </transition>
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
