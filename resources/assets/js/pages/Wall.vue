<template>
    <div class="page page-allow-overflow">
        <div id="wall-content">
            <photo-wall/>
        </div>
        <footer-component/>
    </div>
</template>
<script>

    import PhotoWall from '../components/PhotoWall.vue';
    import Footer from '../components/Footer.vue';

    export default {
        name: 'Wall',
        data() {
            return {
            }
        },
        components: {
            PhotoWall,
            'footer-component': Footer
        }
    }
</script>
