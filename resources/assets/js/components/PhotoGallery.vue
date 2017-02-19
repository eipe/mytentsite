<template>
    <div v-if="isActive">
        <div class="overlay" @click="destroy"></div>
        <div class="photo-view">
            <div style="width: 70%">
            <photo
                :id="activePhoto.id"
                :thumbnail="activePhoto.thumbnail"
                :img_location="activePhoto.img_location"
                :caption="activePhoto.caption"
                :likes="activePhoto.likes"
                :lat="activePhoto.lat"
                :lng="activePhoto.lng"></photo>
            </div>
            <div class="photo-info" style="width: 30%">
                <i class="fa fa-times close" @click="destroy"></i>
                <strong>{{ activePhoto.reported_by }}</strong><br>
                <small>{{ activePhoto.created_at }}</small><br><br>
                <small>{{ activePhoto.caption }}</small>
                <br>
                <i class="pointer fa" v-bind:class="likeIcon" @click="toggleLike"></i> {{ activePhoto.likes }}
            </div>
        </div>
    </div>
</template>
<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #222;
        opacity: 0.9;
        z-index: 1000;
        overflow: hidden;
    }

    .photo-view {
        background-color: #fff;
        position: absolute;
        width: 765px;
        height: 400px;
        top: calc(50% - 200px);
        left: calc(50% - 400px);
        z-index: 1001;
    }

    .photo-view > div {
        display: inline-block;
        box-sizing: border-box;
        clear: none;
        float: left;
    }

    .photo-view .photo-container {
        max-height: 100%;
        max-width: 100%;
        overflow: hidden;
        margin: 0;
    }

    .photo-info {
        height: 100%;
        box-sizing: border-box;
        padding: 10px;
    }

    .photo-info .photo-comments {
        height: auto;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .close {
        position: absolute;
        right: 0;
        top: 0;
        cursor: pointer;
    }
</style>
<script>

    import Photo from './Photo.vue'

    export default {
        data() {
            return {
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
            },
            toggleLike() {
                if(this.activePhoto.hasLiked) {
                    this.$store.dispatch('unlikePhoto', this.activePhoto.id);
                } else {
                    this.$store.dispatch('likePhoto', this.activePhoto.id);
                }
            }
        },
        components: {
            Photo
        }
    }
</script>
