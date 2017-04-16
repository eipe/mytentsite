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
                                        <div class="level-item">
                                            <i class="fa is-clickable" title="Bookmark tentsite"
                                               v-bind:class="likeIcon" @click="toggleLike"></i>
                                            &nbsp;&nbsp;{{ activePhoto.likes }}
                                        </div>
                                        <div class="level-item">
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
                                <photo-comment-form :id="activePhoto.id" :focus="focus" />
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
                hasLiked: false,
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
            likeIcon() {
                if(this.hasLiked) {
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
                this.hasLiked = false;
                this.focus = false;
            },
            toggleLike() {
                if(this.hasLiked) {
                    this.hasLiked = false;
                    this.$store.dispatch("unlikePhoto", this.activePhoto.id);
                } else {
                    this.hasLiked = true;
                    this.$store.dispatch("likePhoto", this.activePhoto.id);
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
                    this.hasLiked = photo.hasLiked;
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
