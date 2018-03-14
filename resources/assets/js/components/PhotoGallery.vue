<template>
    <div :data-tent-sites="tentSites" v-if="isActive">
        <div class="modal" v-bind:class="{ 'is-active' : isActive }">
            <div class="modal-background" @click="destroy"></div>
            <transition enter-active-class="animated zoomIn">
                <div v-show="isActive">
                    <transition enter-active-class="animated slideInDown">
                        <div class="modal-content is-fullheight is-marginless" v-if="activePage === 'photo'">
                            <div class="content is-paddingless is-marginless">
                                <div class="card" id="photo-container">
                                    <div class="card-image">
                                        <figure class="image is-4by3">
                                            <img :src="activeTentSite.img_location" />
                                            <div class="modal-navigate modal-navigate-prev is-clickable"
                                                 @click.prevent="navigatePrev"></div>
                                            <div class="modal-navigate modal-navigate-next is-clickable"
                                                 @click.prevent="navigateNext"></div>
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <div class="media">
                                            <div class="media-left">
                                                <p class="title is-4">{{ activeTentSite.reported_by }}</p>
                                                <p class="subtitle is-6">{{ activeTentSite.created_at }}</p>
                                            </div>
                                            <div class="media-right">
                                                <nav class="level">
                                                    <div class="level-left">
                                                        <div class="level-item">
                                                            <span @click="toggleComment"
                                                                  class="button is-small tooltip is-tooltip-top"
                                                                  data-tooltip="View comments and/or add your own">Comments ({{ commentsCount }})</span>
                                                        </div>
                                                        <div class="level-item">
                                                            <span @click="viewOnMap" data-tooltip="View tent site on map"
                                                                  class="button is-small tooltip is-tooltip-top">View on map</span>
                                                        </div>
                                                         <div class="level-item">
                                                            <span class="tooltip is-tooltip-top is-tooltip-multiline"
                                                                  data-tooltip="Click to add/remove tent site bookmark. Review your bookmarks in user profile">
                                                                <i class="fa"
                                                                   :title="bookmarkTitle"
                                                                   v-bind:class="bookmarkIcon"
                                                                   @click="toggleBookmark"></i>
                                                            </span>
                                                            &nbsp;&nbsp;{{ bookmarks.length }}
                                                        </div>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                        <div class="content">{{ activeTentSite.caption }}</div>
                                        <div class="content is-small" v-if="activeTentSite.taken_date">
                                            Photo was taken {{ activeTentSite.taken_date }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                    <transition enter-active-class="animated slideInUp">
                        <div class="modal-card is-paddingless is-marginless" v-if="activePage === 'comments'">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Comments ({{ commentsCount }})</p>
                                <span class="button is-link is-pulled-right"
                                      @click.prevent="toggleActivePage()">Back</span>
                            </header>
                            <section class="modal-card-body">
                                <photo-comments :comments="comments" id="photo-comments" />
                            </section>
                            <footer class="modal-card-foot">
                                <photo-comment-form :tent-site="activeTentSite" :focus="focus"
                                                    v-if="isUserActionsAvailable" />
                            </footer>
                        </div>
                    </transition>
                    <button class="modal-close" @click="destroy"></button>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>

    import PhotoComments from './PhotoComments.vue'
    import PhotoCommentForm from './PhotoCommentForm.vue'

    export default {
        name: "PhotoGallery",
        data() {
            return {
                isActive: false,
                focus: false,
                activePage: "photo",
                activeTentSite: null,
                hasUserBookmarked: false,
                order: {}
            }
        },
        props: {
            tentSites: {
                type: Array,
                required: true
            }
        },
        computed: {
            comments() {
                if(this.activeTentSite.comments) {
                    return this.activeTentSite.comments;
                }
                return [];
            },
            commentsCount() {
                if(this.comments) {
                    return this.comments.length;
                }
                return 0;
            },
            bookmarks() {
                if(this.activeTentSite.bookmarks) {
                    return this.activeTentSite.bookmarks;
                }
                return [];
            },
            bookmarkTitle() {
                if(this.isUserActionsAvailable) {
                    if(this.hasUserBookmarked) {
                        return 'Click to remove bookmark';
                    } else {
                        return 'Click to add bookmark';
                    }
                }
                return 'Number of users who has bookmarked this tent site';
            },
            bookmarkIcon() {
                let icon = '';
                if(this.hasUserBookmarked) {
                    icon = "fa-bookmark";
                } else {
                    icon = "fa-bookmark-o";
                }

                if(this.isUserActionsAvailable) {
                    icon += ' is-clickable';
                }
                return icon;
            },
            isUserActionsAvailable() {
                return this.$auth.check();
            }
        },
        created() {
            let me = this;
            window.addEventListener("keyup", function(event) {
                if(me.activeTentSite && event.keyCode === 27) {
                    me.destroy();
                }
            });

            window.addEventListener("keyup", function(event) {
                if(me.activeTentSite) {
                    if(event.keyCode === 37) {
                        me.navigatePrev();
                    } else if(event.keyCode === 39) {
                        me.navigateNext();
                    }
                }
            });
        },
        methods: {
            openGallery(tentSite) {
                this.activeTentSite = tentSite;
                this.isActive = true;
            },
            navigateNext() {
                let indexOfCurrent = this.tentSites.indexOf(this.activeTentSite);
                if(indexOfCurrent === this.tentSites.length-1) {
                    this.activeTentSite = this.tentSites[0];
                } else {
                    this.activeTentSite = this.tentSites[++indexOfCurrent];
                }
            },
            navigatePrev() {
                let indexOfCurrent = this.tentSites.indexOf(this.activeTentSite);
                if(indexOfCurrent === 0) {
                    this.activeTentSite = this.tentSites[this.tentSites.length-1];
                } else {
                    this.activeTentSite = this.tentSites[--indexOfCurrent];
                }
            },
            destroy() {
                this.focus = false;
                this.activePage = "photo";
                this.activeTentSite = null;
                this.isActive = false;
            },
            toggleBookmark() {
                if(this.isUserActionsAvailable && this.activeTentSite) {
                    let me = this;
                    if(this.hasUserBookmarked) {
                        this.$store.dispatch("removeBookmark", this.activeTentSite).then(function() {
                            let indexOfUser = me.activeTentSite.bookmarks.indexOf(me.$auth.user().id);
                            if(indexOfUser > -1) {
                                me.bookmarks.splice(indexOfUser, 1);
                            }
                            me.hasUserBookmarked = false;
                        });
                    } else {
                        this.$store.dispatch("addBookmark", this.activeTentSite).then(function() {
                            me.bookmarks.push(me.$auth.user().id);
                            me.hasUserBookmarked = true;
                        });
                    }
                }
            },
            toggleActivePage() {
                this.activePage = (this.activePage === "photo" ? "comments" : "photo");
            },
            viewOnMap() {
                if(this.activeTentSite) {
                    this.$store.dispatch("viewPhotoOnMap", this.activeTentSite);
                }
            },
            toggleComment() {
                this.toggleActivePage();
                this.focus = true;
            }
        },
        watch: {
            activeTentSite(tentSite) {
                if(!tentSite) {
                    this.hasUserBookmarked = false;
                    return;
                }

                let me = this;
                if(typeof tentSite.comments == "undefined") {
                    Vue.axios.get('comments/' + this.activeTentSite.id).then(function handleResponse(response) {
                        if(typeof response.data !== typeof undefined) {
                            for (let commentKey in response.data.data) {
                                if(!response.data.data.hasOwnProperty(commentKey)) {
                                    continue;
                                }
                                me.$store.dispatch(
                                    "addCommentOnPhoto", {
                                        id: me.activeTentSite.id,
                                        comment: response.data.data[commentKey]
                                    }
                                );
                            }
                        }
                    });
                }

                if(!this.$auth.user().id) {
                    this.hasUserBookmarked = false;
                    return;
                }
                this.hasUserBookmarked = (this.bookmarks.indexOf(this.$auth.user().id) > -1);
            }
        },
        components: { PhotoComments, PhotoCommentForm }
    }
</script>
