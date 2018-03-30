<template>
    <div class="content">
        <article class="media">
            <div class="media-content">
                <strong>{{ commentBy }}</strong> <small>{{ createdAt }}</small>
                <br>
                {{ comment }}
            </div>
            <div class="media-right">
                <button class="button is-danger" @click="deleteComment()" v-bind:class="{ 'is-loading' : isDeleting }" v-if="isUserCreator">Delete</button>
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
                // Todo: Replace with user id when available
                if(this.$auth.user().name === this.commentBy) {
                    return true;
                }
                return false;
            }
        },
        methods: {
            deleteComment() {
                this.isDeleting = true;
                Vue.axios.post('/comment/' + this.commentId + '/delete').then(success => {
                    this.isDeleting = false;
                    this.$store.dispatch(
                        "removeCommentFromPhoto",
                        { tentSiteId: this.tentSiteId, comment: this}
                    );
                }, error => {
                    this.isDeleting = false;
                    this.$store.dispatch("displayError", "Could not delete comment. Please try again");
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
