<template>
    <div class="photo-controllers" :data-photo-controllers-id="id">
        <i class="photo-view-map fa fa-map-marker is-clickable" title="View photo on map" @click.stop="viewOnMap"></i>
    </div>
</template>
<script>

    import Map from '../pages/Map.vue';

    export default {
        name: 'Photo controllers',
        data() {
            return {
            }
        },
        props: ["id"],
        components: { Map },
        methods: {
            viewOnMap() {
                this.$store.dispatch('viewPhotoOnMap', this.id);
            }
        }
    }
</script>
