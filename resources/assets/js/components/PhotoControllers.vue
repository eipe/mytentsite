<template>
    <div class="photo-controllers" :data-photo="photo">
        <i class="photo-view-map fa fa-map-marker is-clickable" title="View photo on map" @click.stop="viewOnMap"></i>
    </div>
</template>
<script>

    import Map from "../pages/Map.vue";

    export default {
        name: "PhotoControllers",
        data() {
            return {
            }
        },
        props: ["photo"],
        components: { Map },
        methods: {
            viewOnMap() {
                this.$store.dispatch("viewPhotoOnMap", this.photo);
            }
        }
    }
</script>
