<template>
    <div class="has-page-margin-top">
        <div class="container">
            <photo-wall/>
        </div>
        <br>
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
            "footer-component": Footer
        }
    }
</script>
