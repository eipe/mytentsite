<template>
    <div class="page page-allow-overflow">
        <nav class="nav white">
            <div class="nav-center">
                <div class="nav-item"><router-link to="/user/profile">Profile</router-link></div>
                <div class="nav-item"><router-link to="/user/contributions">Contributions</router-link></div>
            </div>
        </nav>
        <transition enter-active-class="animated fadeIn">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
        <footer-component/>
    </div>
</template>
<script>

    import Footer from '../components/Footer.vue';

    export default {
        name: 'User',
        data() {
            return {
            }
        },
        components: {
            'footer-component': Footer
        }
    }
</script>
