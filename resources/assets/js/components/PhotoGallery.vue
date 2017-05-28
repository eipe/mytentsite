<template>
    <div>
        <div class="modal" v-bind:class="{ 'is-active' : isActive }">
            <div class="modal-background" @click="destroy"></div>
            <transition enter-active-class="animated zoomIn">
                <div v-show="isActive">
                    <transition enter-active-class="animated slideInDown">
                        <div class="modal-content" v-if="activePage === 'photo'">
                            <div class="content is-paddingless is-marginless">
                                <div class="card" id="photo-container">
                                    <div class="card-image">
                                        <figure class="image is-4by3">
                                            <img :src="activePhoto.img_location" />
                                            <div class="modal-navigate modal-navigate-prev is-clickable"
                                                 @click.prevent="navigatePrev" v-if="!isFirstPhoto"></div>
                                            <div class="modal-navigate modal-navigate-next is-clickable"
                                                 @click.prevent="navigateNext" v-if="!isLastPhoto"></div>
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <div class="media">
                                            <div class="media-left">
                                                <p class="title is-4">{{ activePhoto.reported_by }}</p>
                                                <p class="subtitle is-6">{{ activePhoto.created_at }}</p>
                                            </div>
                                            <div class="media-right">
                                                <nav class="level">
                                                    <div class="level-left">
                                                        <div class="level-item">
                                                            <i class="fa" :title="bookmarkTitle"
                                                               v-bind:class="bookmarkIcon" @click="toggleBookmark"></i>
                                                            &nbsp;&nbsp;{{ activePhoto.bookmarks }}
                                                        </div>
                                                        <div class="level-item"
                                                             v-if="isUserActionsAvailable">
                                                <span @click="toggleComment"
                                                      class="button is-small">Comments ({{ commentsCount }})</span>
                                                        </div>
                                                        <div class="level-item">
                                                <span @click="viewOnMap" title="View tentsite on map"
                                                      class="button is-small">View on map</span>
                                                        </div>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                        <div class="content">{{ activePhoto.caption }}</div>
                                        <div class="content is-small" v-if="activePhoto.taken_date">
                                            Photo was taken {{ activePhoto.taken_date }}
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
                                <photo-comment-form :id="activePhoto.id" :focus="focus"
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

    import Photo from './Photo.vue'
    import PhotoComments from './PhotoComments.vue'
    import PhotoCommentForm from './PhotoCommentForm.vue'

    export default {
        name: 'PhotoGallery',
        data() {
            return {
                hasUserBookmarked: false,
                focus: false,
                activePage: "photo",
                comments: [],
                isFirstPhoto: false,
                isLastPhoto: false
            }
        },
        computed: {
            isActive() {
                return this.$store.state.gallery.isActive;
            },
            activePhoto() {
                let photo = this.$store.state.gallery.activePhoto;
                if(photo) {
                    return photo;
                }
            },
            commentsCount() {
                if(this.comments) {
                    return this.comments.length;
                }
                return 0;
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
            var me = this;
            window.addEventListener("keyup", function(event) {
                if(me.isActive && event.keyCode === 27) {
                    me.destroy();
                }
            });

            window.addEventListener("keyup", function(event) {
                if(me.isActive) {
                    if(event.keyCode === 37) {
                        me.navigatePrev();
                    } else if(event.keyCode === 39) {
                        me.navigateNext();
                    }
                }
            });
        },
        methods: {
            navigateNext() {
                this.$store.dispatch("galleryNavigateNext");
            },
            navigatePrev() {
                this.$store.dispatch("galleryNavigatePrev");
            },
            destroy() {
                this.$store.dispatch("destroyGallery");
                this.comments = null;
                this.hasUserBookmarked = false;
                this.focus = false;
                this.activePage = "photo";
            },
            toggleBookmark() {
                if(this.isUserActionsAvailable) {
                    if(this.hasUserBookmarked) {
                        this.hasUserBookmarked = false;
                        this.$store.dispatch("removeBookmark", this.activePhoto.id);
                    } else {
                        this.hasUserBookmarked = true;
                        this.$store.dispatch("addBookmark", this.activePhoto.id);
                    }
                }
            },
            toggleActivePage() {
                this.activePage = (this.activePage == "photo" ? "comments" : "photo");
            },
            viewOnMap() {
                this.$store.dispatch("viewPhotoOnMap", this.activePhoto);
            },
            toggleComment() {
                this.toggleActivePage();
                this.focus = true;
            }
        },
        watch: {
            activePhoto(photo) {
                if(typeof photo.id !== typeof undefined) {
                    this.hasUserBookmarked = photo.hasUserBookmarked;
                    let me = this;

                    me.isFirstPhoto = (photo.id === me.$store.state.tentSites.firstPhotoId);
                    me.isLastPhoto = (photo.id === me.$store.state.tentSites.lastPhotoId);

                    if(typeof photo.comments !== typeof undefined) {
                        me.comments = photo.comments;
                    } else {
                        Vue.axios.get("comments/" + photo.id).then(function handleSuccess(response) {
                            if(typeof response.data !== typeof undefined) {
                                let comments = Object.keys(response.data.data).map(key => response.data.data[key]);
                                me.$store
                                    .dispatch("addCommentsOnPhoto", { id: photo.id, comments: comments })
                                    .then(() => { me.comments = comments });
                            }
                        }).catch(function handleError(error) {
                            me.$store.dispatch("displayError", "Could not load comments.<br>Please try again later");
                        });
                    }
                }
            }
        },
        components: { Photo, PhotoComments, PhotoCommentForm }
    }
</script>
