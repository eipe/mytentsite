<template>
    <header>
        <nav class="navbar is-fixed-top is-light" role="navigation" aria-label="main navigation">
            <div class="container">
                <div class="navbar-brand">
                    <router-link to="/info" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="View information about this project">
                            mytentsite
                        </span>
                    </router-link>
                    <router-link to="/share" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Share a tent site">
                            <i class="fa fa-camera fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/locate" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Locate shared tent sites">
                            <i class="fa fa-map fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/explore" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Explore shared tent sites">
                            <i class="fa fa-th fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/user" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="User profile"
                              v-bind:class="{ 'has-text-info' : isLoggedIn }">
                            <i class="fa fa-user fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/admin" class="navbar-item has-text-centered" active-class="is-active" v-if="isAdmin">
                        <span class="tooltip is-tooltip-bottom" data-tooltip="Administrator">
                            <i class="fa fa-unlock-alt fa-lg"></i>
                        </span>
                    </router-link>
                </div>
            </div>
        </nav>
    </header>
</template>
<script>
    export default {
        name: "Header",
        computed: {
            isLoggedIn() {
                return (this.$auth.user().name);
            },
            isAdmin() {
                if(!this.isLoggedIn) {
                    return false;
                }
                return this.$auth.user().is_admin;
            }
        }
    }
</script>