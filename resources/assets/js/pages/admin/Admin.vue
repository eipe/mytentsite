<template>
    <div class="" v-if="this.$auth.check('admin')">
        <section class="hero is-dark">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">{{ $t('title')}}</h1>
                    <h2 class="subtitle">{{ $t('subTitle')}}</h2>
                </div>
            </div>
            <div class="hero-foot">
                <div class="container">
                    <div class="tabs is-boxed">
                        <ul>
                            <router-link to="/admin/dashboard" tag="li"><a>{{ $t('page.dashboard')}}</a></router-link>
                            <router-link to="/admin/tentsites" tag="li"><a>{{ $t('page.tentSites')}}</a></router-link>
                            <router-link to="/admin/users" tag="li"><a>{{ $t('page.users')}}</a></router-link>
                            <router-link to="/admin/tags" tag="li"><a>{{ $t('page.tags')}}</a></router-link>
                            <router-link to="/admin/statistics" tag="li"><a>{{ $t('page.statistics')}}</a></router-link>
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
        i18n: {
            messages: {
                en: {
                    title: 'Administrator\'s homeground',
                    subTitle: 'Make sure that the tent site is up and running!',
                    loadError: 'You don\'t have access to this page and was therefore redirected back to the main page',
                },
                no: {
                    title: 'Administrator\'s hjemsted',
                    subTitle: 'Sørg for at mytentsite er oppe å går!',
                    loadError: 'Du har ikke tilgang til denne siden, og vil derfor bli sendt til forsiden',
                }
            }
        },
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
                    this.$t('loadError')
                );
                Vue.router.push({
                    path: "/"
                });
            }
        }
    }
</script>
