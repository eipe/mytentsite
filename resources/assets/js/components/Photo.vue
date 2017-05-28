<template>
    <div class="photo-container"
        :data-photo-id="id"
        :data-photo-latitude="lat"
        :data-photo-longitude="lng"
        :data-photo-caption="caption"
        :data-photo-reported-by="reported_by"
        :data-photo-created-at="created_at"
        :data-photo-bookmarks="bookmarks"
        :data-photo-location="img_location"
        :data-photo-approved="approved"
         @mouseenter="displayControllers()"
         @mouseleave="hideControllers()"
         @click="openImage">
        <progressive-img
                :src="img_location"
                :placeholder="thumbnail"
                :blur="5" class="is-clickable" />
        <slot v-if="isControllersVisible">
            <photo-controllers :photo="this"></photo-controllers>
        </slot>
        <div v-if="showDetails">
            Status: {{ status }}
        </div>
    </div>
</template>
<style>
    .photo-controllers {
        position: absolute;
        top: calc(50% - 20px);
        left: calc(50% - 20px);
        width: 40px;
        height: 40px;
        text-align: center;
        z-index: 1;
    }

    .photo-controllers i {
        color: #eee;
        opacity: 0.6;
        font-size: 40px;
        display: inline-block;
        margin: 0 15px;
        text-shadow: 1px 1px 1px #333;
    }

    .photo-controllers i:hover,
    .photo-container.photo-focus .photo-controllers i {
        opacity: 0.9;
    }

    /* Photo wall */
    .photo-container {
        position: relative;
    }
</style>
<script>
    import PhotoControllers from "./PhotoControllers.vue"

    export default {
        name: "Photo",
        data () {
            return{
                isControllersVisible: false
            }
        },
        props: {
            id: {
                type: Number,
                required: true
            },
            img_location: {
                type: String,
                required: true
            },
            thumbnail: {
                type: String,
                required: true
            },
            lat: {
                type: Number,
                required: true
            },
            lng: {
                type: Number,
                required: true
            },
            caption: {
                type: String,
                required: true
            },
            created_at: {
                type: String,
                required: false
            },
            reported_by: {
                type: String,
                required: false
            },
            bookmarks: {
                type: Number,
                required: true
            },
            approved: {
                type: Number,
                default: 0,
                required: false
            },
            showDetails: {
                type: Boolean,
                default: false
            },
            showControllers: {
                type: Boolean,
                default: true
            }
        },
        computed: {
            status() {
                if(this.approved > 0) {
                    return "Approved";
                } else if(this.approved < 0) {
                    return "Not approved";
                } else {
                    return "Waiting for approval";
                }
            }
        },
        components:{
            PhotoControllers
        },
        methods: {
            openImage() {
                if(this.isControllersVisible) {
                    this.$store.dispatch("openPhoto", this);
                }
            },
            displayControllers() {
                if(this.showControllers) {
                    this.isControllersVisible = true;
                }
            },
            hideControllers() {
                this.isControllersVisible = false;
            }
        },
    }
</script>
