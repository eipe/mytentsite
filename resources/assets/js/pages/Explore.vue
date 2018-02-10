<template>
    <div class="has-fixed-header">
        <photo-wall/>
        <br>
        <footer-component/>
    </div>
</template>
<script>

    import PhotoWall from '../components/PhotoWall.vue';
    import Footer from '../components/Footer.vue';

    export default {
        name: 'Explore',
        data() {
            return {
            }
        },
        components: {
            PhotoWall,
            "footer-component": Footer
        }
    }
</script>
