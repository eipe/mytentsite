<template>
    <div class="content">
        <article class="media">
            <div class="media-content">
                <strong>{{ commentByName }}</strong> <small>{{ createdAt }}</small>
                <br>
                {{ comment }}
            </div>
            <div class="media-right">
                <button class="button is-danger"
                        @click="deleteComment()"
                        v-bind:class="{ 'is-loading' : isDeleting }"
                        v-if="isUserCreator">
                    {{ $t('action.delete')}}
                </button>
            </div>
        </article>
    </div>
</template>
<script>
    export default {
        name: "PhotoComment",
        data() {
            return {
                isDeleting: false
            }
        },
        computed: {
            isUserCreator() {
                return this.$auth.user().id === this.commentBy;
            }
        },
        methods: {
            deleteComment() {
                this.isDeleting = true;
                let me = this;
                Vue.axios.post('/comment/' + this.commentId + '/delete').then(() => {
                    me.isDeleting = false;
                    me.$store.dispatch(
                        'removeCommentFromPhoto',
                        { tentSiteId: this.tentSiteId, comment: this}
                    );
                }, () => {
                    me.isDeleting = false;
                    me.$store.dispatch(
                        'displayError',
                        me.$t('error.couldNotDelete', me.$t('comment', 1).toLowerCase())
                    );
                });
            }
        },
        props: {
            "tentSiteId": {
                type: Number,
                required: true
            },
            "commentId": {
                type: Number,
                required: true
            },
            "commentBy": {
                type: Number,
                required: true
            },
            "commentByName": {
                type: String,
                required: true
            },
            "createdAt": {
                type: String,
                require: true
            },
            "comment": {
                type: String,
                required: true
            }
        }
    }
</script>
