<template>
    <div>
        <section class="section container">
            <div class="tags">
                <span v-for="tag in tags"
                      :data-id="tag.id"
                      class="tag is-clickable"
                      @click="toggleTagFilter(tag)"
                      v-bind:class="{'is-success' : isTagFilterActive(tag)}">
                    {{ tag.name }}
                </span>
            </div>
            <div class="columns is-multiline is-mobile has-normal-margin">
                <div v-for="tentSite in tentSites"
                     class="column is-one-third"
                     :key="tentSite.id"
                     @click="openGallery(tentSite)">
                    <photo :tent-site="tentSite"></photo>
                </div>
            </div>
            <div class="container has-text-centered">
                <button class="button is-info"
                        v-if="hasMore"
                        @click="loadMore"
                        v-bind:class="{ 'is-loading' : isLoadingMore }">{{ $t('action.loadMore')}}</button>
                <button class="button" disabled v-else>{{ $t('misc.allLoaded')}}</button>
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
                tags: [],
                filter: {
                    tags: []
                }
            }
        },
        created() {
            if(typeof this.tentSites === typeof undefined || this.tentSites.length === 0) {
                this.loadMoreTentSites();
            }

            let me = this;

            Vue.axios.get('tags').then((response) => {
                response.data.data.forEach((tagData) => {
                    let tag = {
                        id: tagData.id,
                        name: tagData.name
                    };
                    me.tags.push(tag);
                });
            });
        },
        methods: {
            toggleTagFilter(tag) {
                let tagIndex = this.filter.tags.indexOf(tag);

                if(tagIndex > -1) {
                    this.filter.tags.splice(tagIndex, 1);
                } else {
                    this.filter.tags.push(tag);
                }
            },
            isTagFilterActive(tag) {
                return (this.filter.tags.indexOf(tag) > -1);
            },
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            loadMore() {
                let me = this;
                this.isLoadingMore = true;
                this.loadMoreTentSites().then(() => {
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
                let me = this,
                    tentSites = [],
                    filterByTags = (me.filter.tags.length > 0);

                me.tentSiteIds.forEach((id) => {
                    if(me.$store.state.tentSites.hasOwnProperty(id)) {
                        let tentSite = me.$store.state.tentSites[id];
                        if(filterByTags) {
                            let matchingFilters = me.filter.tags.filter((tag) => {
                                if(tentSite.tags.indexOf(tag.name) > -1) {
                                    return tag;
                                }
                            });
                            if(matchingFilters.length > 0) {
                                tentSites.push(tentSite);
                            }
                        } else {
                            tentSites.push(tentSite);
                        }
                    }
                });
                return tentSites;
            }
        },
        components: {
            Photo,
            PhotoGallery,
            "footer-component": Footer
        }
    }
</script>
