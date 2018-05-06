<template>
    <div class="photo-container"
        :data-tent-site="tentSite"
         @mouseenter="displayControllers()"
         @mouseleave="hideControllers()">
        <img :src="tentSite.thumbnail" class="is-clickable" />
        <slot v-if="isControllersVisible">
            <photo-controllers :photo="tentSite"></photo-controllers>
        </slot>
        <div v-if="showDetails">
            {{ $t('status')}}: {{ tentSite.status }}
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
            tentSite: {
                type: Object,
                required: true
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
                    return this.$t('tentSite.state.approved');
                } else if(this.approved < 0) {
                    return this.$t('tentSite.state.notApproved');
                } else {
                    return this.$t('tentSite.state.waitingApproval');;
                }
            }
        },
        components:{
            PhotoControllers
        },
        methods: {
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
