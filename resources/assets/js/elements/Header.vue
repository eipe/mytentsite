<template>
    <header>
        <nav class="navbar is-fixed-top is-light" role="navigation" aria-label="main navigation">
            <div class="container">
                <div class="navbar-brand">
                    <router-link to="/info" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" :data-tooltip="$t('misc.viewInfo')">
                            mytentsite
                        </span>
                    </router-link>
                    <router-link to="/share" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" :data-tooltip="$t('tentSite.shareTentSite')">
                            <i class="fa fa-camera fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/locate" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" :data-tooltip="$tc('tentSite.locateTentSite', 2)">
                            <i class="fa fa-map fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/explore" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" :data-tooltip="$tc('tentSite.exploreTentSite', 2)">
                            <i class="fa fa-th fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/user" class="navbar-item has-text-centered" active-class="is-active">
                        <span class="tooltip is-tooltip-bottom" :data-tooltip="$t('profile.profile')"
                              v-bind:class="{ 'has-text-info' : isLoggedIn }">
                            <i class="fa fa-user fa-lg"></i>
                        </span>
                    </router-link>
                    <router-link to="/admin" class="navbar-item has-text-centered" active-class="is-active" v-if="isAdmin">
                        <span class="tooltip is-tooltip-bottom" :data-tooltip="$t('role.admin')">
                            <i class="fa fa-unlock-alt fa-lg"></i>
                        </span>
                    </router-link>
                    <div class="navbar-item has-text-centered dropdown is-hoverable">
                        <div class="dropdown-trigger">
                            <span class="icon" aria-haspopup="true" aria-controls="dropdown-menu-language">
                                <i class="fa fa-flag fa-lg"></i>
                            </span>
                        </div>
                        <div class="dropdown-menu" id="dropdown-menu-language" role="menu">
                            <div class="dropdown-content has-text-left">
                                <div class="dropdown-item is-clickable"
                                     v-bind:class="{'has-text-weight-bold' : isSelectedLanguage('en') }"
                                     @click="setLanguage('en')">
                                    {{ $t('language.english')}}
                                </div>
                                <div class="dropdown-item is-clickable"
                                     v-bind:class="{'has-text-weight-bold' : isSelectedLanguage('no') }"
                                     @click="setLanguage('no')">
                                    {{ $t('language.norwegian')}}
                                </div>
                            </div>
                        </div>
                    </div>
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
        },
        methods: {
            isSelectedLanguage(lng) {
                return (this.$i18n.locale === lng);
            },
            setLanguage(lng) {
                this.$store.dispatch('changeLanguage', lng);
                if(this.isLoggedIn) {
                    Vue.axios.put('language', {language: lng});
                }
            }
        }
    }
</script>