<template>
    <div class="photo-controllers is-hidden-mobile" :data-photo="photo">
        <span class="tooltip is-tooltip-top" data-tooltip="Locate this tent site">
            <i class="photo-view-map fa fa-map-marker is-clickable"  @click.stop="viewOnMap"></i>
        </span>
    </div>
</template>
<script>

    import Locate from "../pages/Locate.vue";

    export default {
        name: "PhotoControllers",
        data() {
            return {
            }
        },
        props: ["photo"],
        components: { Locate },
        methods: {
            viewOnMap() {
                this.$store.dispatch("viewPhotoOnMap", this.photo);
            }
        }
    }
</script>
