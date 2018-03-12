<template>
    <div>
        <section class="hero">
            <div class="hero-body">
                <div class="container">
                    <h2 class="title">Your bookmarks - {{ tentSites.length}} in total</h2>
                    <button class="button" @click.prevent="loadTentSites"
                            v-if="!isLoaded" v-bind:class="{ 'is-loading disabled' : isLoading }">Try again</button>
                    <div class="columns is-multiline is-mobile">
                        <div class="column is-2" v-for="tentSite in tentSites">
                            <img :src="tentSite.thumbnail" class="is-clickable" @click="openGallery(tentSite)" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <photo-gallery :tent-sites="tentSites" ref="gallery"></photo-gallery>
    </div>
</template>
<script>

    import Photo from "../../components/Photo.vue"
    import PhotoGallery from "../../components/PhotoGallery.vue"

    export default {
        name: "User-bookmarks",
        data() {
            return {
                tentSites: [],
                isLoaded: false,
                isLoading: false,
            }
        },
        methods: {
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            addTentSite(tentSite) {
                this.tentSites.push(tentSite);
            },
            loadTentSites() {
                let me = this;
                me.isLoading = true;
                Vue.axios.get("bookmarks").then(function success(success) {
                    if(typeof success.data !== typeof undefined) {
                        success.data.data.forEach(function (tentSite) {
                            // Todo: Fix API and provide correct path to images
                            tentSite.thumbnail = '/storage/photos/tentsite_thumbnails/' + tentSite.img_location;
                            tentSite.img_location = '/storage/photos/tentsites/' + tentSite.img_location;
                            tentSite.bookmarks = [];
                            me.addTentSite(tentSite);
                        });
                        me.isLoaded = true;
                        me.isLoading = false;
                    }
                }).catch(function error(error) {
                    me.isLoading = false;
                    me.isLoaded = false;
                    me.$store.dispatch("displayError", "Could not load your bookmarks. <br>Please try again later");
                });
            }
        },
        created() {
           this.loadTentSites();
        },
        components: {
            PhotoGallery,
            Photo
        }
    }
</script>
