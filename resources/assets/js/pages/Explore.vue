<template>
    <div>
        <section class="section container">
            <div class="columns is-multiline is-mobile has-normal-margin">
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
        </section>
        <footer-component/>
        <photo-gallery ref="gallery" :tent-sites="tentSites"></photo-gallery>
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
                apiUrl: "tentsites",
                isLoadingMore: false,
                tentSiteIds: [],
                hasMore: true,
                firstPhotoId: null,
                lastPhotoId: null,
            }
        },
        created() {
            if(typeof this.tentSites === typeof undefined || this.tentSites.length === 0) {
                this.loadMoreTentSites();
            }
        },
        methods: {
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            loadMore() {
                let me = this;
                this.isLoadingMore = true;
                this.loadMoreTentSites().then(function() {
                    me.isLoadingMore = false;
                });
            },
            loadMoreTentSites() {
                let me = this;
                return new Promise((resolve, reject) => {
                    if(!me.hasMore) {
                        reject("Has no more tent sites");
                    }

                    Vue.axios.get(me.apiUrl).then(function(response) {
                        let responseData = response.data.data;
                        if(responseData.next_page_url) {
                            me.apiUrl = responseData.next_page_url;
                        } else {
                            me.hasMore = false;
                        }

                        if(typeof responseData.data !== typeof undefined && responseData.data.length > 0) {
                            let lastNewPhotoId = null;
                            responseData.data.forEach(function (photo) {
                                if(!me.firstPhotoId) {
                                    me.firstPhotoId = photo["id"];
                                }
                                me.$store.dispatch("addTentSite", photo);
                                me.tentSiteIds.push(photo["id"]);
                                lastNewPhotoId = photo["id"];
                            });
                            me.lastPhotoId = lastNewPhotoId;
                        }
                        resolve();
                    }).catch(function(error) {
                        reject(error);
                    });
                });
            }
        },
        computed: {
            tentSites() {
                let me = this;
                return me.tentSiteIds.map(function (id) {
                    if(me.$store.state.tentSites.hasOwnProperty(id)) {
                        return me.$store.state.tentSites[id];
                    }
                });
            }
        },
        components: {
            Photo,
            PhotoGallery,
            "footer-component": Footer
        }
    }
</script>
