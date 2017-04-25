<template>
    <div>
        <div class="modal" v-bind:class="{ 'is-active' : isActive }">
            <div class="modal-background" @click="destroy"></div>
            <transition enter-active-class="animated zoomIn">
                <div class="modal-content" style="width: 80%;" v-show="isActive">
                    <div class="content is-paddingless is-marginless">
                        <div class="columns is-paddingless is-marginless">
                            <div class="column is-paddingless">
                                <img :src="activePhoto.img_location" />
                            </div>
                            <div class="column is-4" style="background-color: #fff;">
                                <div class="media">
                                    <div class="media-content">
                                        <p><strong>{{ activePhoto.reported_by }}</strong>
                                            <small>{{ activePhoto.created_at }}</small>
                                        </p>
                                    </div>
                                </div>
                                <p>{{ activePhoto.caption }}</p>
                                <nav class="level">
                                    <div class="level-left">
                                        <div class="level-item" v-if="activePhoto.showControllers">
                                            <i class="fa is-clickable" title="Bookmark tentsite"
                                               v-bind:class="bookmarkIcon" @click="toggleBookmark"></i>
                                            &nbsp;&nbsp;{{ activePhoto.bookmarks }}
                                        </div>
                                        <div class="level-item" v-if="activePhoto.showControllers">
                                            <span @click="checkIn"
                                                  class="button is-small">Check in</span>
                                        </div>
                                        <div class="level-item">
                                            <span @click="viewOnMap" title="View tentsite on map"
                                                  class="button is-small">View on map</span>
                                        </div>
                                    </div>
                                </nav>
                                <hr>
                                <photo-comments :comments="comments" id="photo-comments" />
                                <photo-comment-form :id="activePhoto.id" :focus="focus"
                                                    v-if="activePhoto.showControllers" />
                            </div>
                        </div>
                    </div>
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
                comments: []
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
            bookmarkIcon() {
                if(this.hasUserBookmarked) {
                    return "fa-bookmark";
                } else {
                    return "fa-bookmark-o";
                }
            }
        },
        created() {
            var me = this;
            window.addEventListener("keyup", function(event) {
                if(me.isActive && event.keyCode === 27) {
                    me.destroy();
                }
            });
        },
        methods: {
            destroy() {
                this.$store.dispatch("destroyGallery");
                this.comments = null;
                this.hasUserBookmarked = false;
                this.focus = false;
            },
            toggleBookmark() {
                if(this.hasUserBookmarked) {
                    this.hasUserBookmarked = false;
                    this.$store.dispatch("removeBookmark", this.activePhoto.id);
                } else {
                    this.hasUserBookmarked = true;
                    this.$store.dispatch("addBookmark", this.activePhoto.id);
                }
            },
            viewOnMap() {
                this.$store.dispatch("viewPhotoOnMap", this.activePhoto.id);
            },
            checkIn() {
                this.focus = true;
                let container = this.$el.querySelector("#photo-comments");
                container.scrollTop = container.scrollHeight;
            }
        },
        watch: {
            activePhoto(photo) {
                if(typeof photo.id !== typeof undefined) {
                    this.hasUserBookmarked = photo.hasUserBookmarked;
                    let me = this;
                    axios.get("comments/" + photo.id).then(function handleSuccess(response) {
                        if(typeof response.data !== typeof undefined) {
                            me.comments = response.data.data;
                        }
                    }).catch(function handleError(error) {
                        me.$store.dispatch("displayError", "Could not load comments.<br>Please try again later");
                    });
                }
            }
        },
        components: { Photo, PhotoComments, PhotoCommentForm }
    }
</script>
