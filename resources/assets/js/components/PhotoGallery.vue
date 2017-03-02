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
                            <div v-if="activePhotoComments" id="photo-comments"
                                 style="max-height: 300px; overflow-y: auto">
                                <div class="content" v-for="comment in activePhotoComments">
                                    <p>
                                        <strong>{{ comment.user_id }}</strong> <small>{{ comment.created_at }}</small>
                                        <br>
                                        {{ comment.comment }}
                                    </p>
                                </div>
                            </div><br>
                            <form @submit.prevent="submitComment">
                                <div class="control is-grouped">
                                    <p class="control is-expanded has-icon has-icon-right">
                                        <input class="input" ref="comment" v-model="comment" type="text"
                                               placeholder="Write a comment">
                                        <span class="icon is-small">
                                            <i class="fa fa-warning" title="Required field"></i>
                                        </span>
                                    </p>
                                    <p class="control">
                                        <button type="submit" class="button is-primary"
                                                v-bind:class="{ 'is-loading' : isPostingComment }">
                                            Post
                                        </button>
                                    </p>
                                </div>
                            </form>
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

    export default {
        data() {
            return {
                comment: '',
                isPostingComment: false
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
            submitComment() {
                let me = this;
                me.isPostingComment = true;
                axios.post('/comments/' + this.activePhoto.id, {
                    comment: this.comment
                }).then(function(response) {
                    me.activePhoto.comments.push(response.data);
                    me.isPostingComment = false;
                    me.comment = '';
                }).catch(function(error) {
                    me.isPostingComment = false;
                });
            },
            writeComment() {
                this.$refs.comment.focus();
                let container = this.$el.querySelector("#photo-comments");
                container.scrollTop = container.scrollHeight;
            }
        }
    }
</script>
