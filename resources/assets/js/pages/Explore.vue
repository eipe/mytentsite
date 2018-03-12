<template>
    <div class="has-fixed-header">
        <photo-gallery ref="gallery" :tent-sites="tentSites"></photo-gallery>
        <div class="container">
            <div class="columns is-multiline is-tablet has-tablet-no-padding has-normal-margin">
                <div v-for="tentSite in tentSites" class="column is-one-third"
                       :key="tentSite.id" @click="openGallery(tentSite)">
                    <photo :tent-site="tentSite"></photo>
                </div>
            </div>
            <div class="container has-text-centered">
                <button class="button is-info" v-if="hasMore" @click="loadMore"
                        v-bind:class="{ 'is-loading' : isLoadingMore }">Load more tent site photos</button>
                <button class="button" disabled v-else>All tent site photos are loaded</button>
            </div>
        </div>
        <br>
        <footer-component/>
    </div>
</template>
<script>

    import Photo from '../components/Photo.vue'
    import PhotoGallery from '../components/PhotoGallery.vue'
    import Footer from '../components/Footer.vue'

    export default {
        name: "Explore",
        data() {
            return {
                isLoadingMore: false
            }
        },
        created() {
            if(typeof this.tentSites === typeof undefined || this.tentSites.length === 0) {
                this.$store.dispatch("loadMoreTentSites");
            }
        },
        methods: {
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            loadMore() {
                let me = this;
                this.isLoadingMore = true;
                this.$store.dispatch("loadMoreTentSites").then(function() {
                    me.isLoadingMore = false;
                });
            }
        },
        computed: {
            hasMore() {
                return this.$store.state.tentSites.hasMore;
            },
            tentSites() {
                return this.$store.state.tentSites.data;
            }
        },
        components: {
            Photo,
            PhotoGallery,
            "footer-component": Footer
        }
    }
</script>
