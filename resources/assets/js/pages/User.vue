<template>
    <div>
        <section class="hero is-info">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">Hi, {{ $auth.user().name }}!</h1>
                </div>
            </div>
            <div class="hero-foot">
                <div class="container">
                    <div class="tabs is-boxed">
                        <ul>
                            <router-link to="/user/profile" tag="li"><a>Profile</a></router-link>
                            <router-link to="/user/bookmarks" tag="li"><a>Bookmarked</a></router-link>
                            <router-link to="/user/contributions" tag="li"><a>Contributions</a></router-link>
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
        <footer-component/>
    </div>
</template>
<script>

    import Footer from "../components/Footer.vue";

    export default {
        name: "User",
        components: {
            "footer-component": Footer
        },
    }
</script>
