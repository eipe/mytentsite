<template>
    <div>
        <section class="hero is-info">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">{{ $t('profile.helloName', [$auth.user().name])}}</h1>
                </div>
            </div>
            <div class="hero-foot">
                <div class="container">
                    <div class="tabs is-boxed">
                        <ul>
                            <router-link to="/user/profile" tag="li">
                                <a>{{ $t('profile.profile')}}</a>
                            </router-link>
                            <router-link to="/user/bookmarks" tag="li">
                                <a>{{ $tc('tentSite.bookmark', 3)}}</a>
                            </router-link>
                            <router-link to="/user/contributions" tag="li">
                                <a>{{ $tc('tentSite.contribution', 3)}}</a>
                            </router-link>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <transition enter-active-class="animated fadeIn">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
    </div>
</template>
<script>
    export default {
        name: "User",
    }
</script>
