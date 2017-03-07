<template>
    <div>
        <div class="modal" v-bind:class="{ 'is-active' : isActive }">
            <div class="modal-background" @click="destroy"></div>
            <div class="modal-content" style="width: 80%;">
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
                                        <i class="fa is-clickable" v-bind:class="likeIcon" @click="toggleLike"></i>
                                        &nbsp;&nbsp;{{ activePhoto.likes }}
                                    </div>
                                    <div class="level-item">
                                        <span @click="viewOnMap" class="button is-small">View on map</span>
                                    </div>
                                    <div class="level-item">
                                        <span @click="writeComment" class="button is-small">Comment</span>
                                    </div>
                                </div>
                            </nav>
                            <hr>
                            <photo-comments :comments="activePhotoComments" id="photo-comments" />
                            <photo-comment-form :id="activePhoto.id" :focus="focus" />
                        </div>
                    </div>
                </div>
                <button class="modal-close" @click="destroy"></button>
            </div>
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
                focus: false
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
            activePhotoComments() {
                return this.activePhoto.comments;
            },
            hasLiked() {
                let photo = this.activePhoto;
                if(photo) {
                    return photo.hasLiked;
                }
                return false;
            },
            likeIcon() {
                if(this.hasLiked) {
                    return 'fa-thumbs-up';
                } else {
                    return 'fa-thumbs-o-up';
                }
            }
        },
        created() {
            var me = this;
            window.addEventListener('keyup', function(event) {
                if(me.isActive && event.keyCode === 27) {
                    me.destroy();
                }
            });
        },
        methods: {
            destroy() {
                this.$store.dispatch('destroyGallery');
                this.comment = null;
                this.focus = false;
            },
            toggleLike() {
                if(this.activePhoto.hasLiked) {
                    this.$store.dispatch('unlikePhoto', this.activePhoto.id);
                } else {
                    this.$store.dispatch('likePhoto', this.activePhoto.id);
                }
            },
            viewOnMap() {
                this.$store.dispatch('viewPhotoOnMap', this.activePhoto.id);
            },
            writeComment() {
                this.focus = true;
                let container = this.$el.querySelector("#photo-comments");
                container.scrollTop = container.scrollHeight;
            }
        },
        components: { Photo, PhotoComments, PhotoCommentForm }
    }
</script>
