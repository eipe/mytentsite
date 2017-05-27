<template>
    <div v-if="comments.length > 0">
        <div class="content" v-for="comment in comments">
            <photo-comment
                :comment_by="comment.user_id"
                :created_at="comment.created_at"
                :comment="comment.comment"/>
        </div>
    </div>
    <div v-else>
        Be the first to leave a comment on this tent site!
    </div>
</template>
<style>
</style>
<script>
    import PhotoComment from "./PhotoComment.vue"
    export default {
        name: "PhotoComments",
        data() {
            return {
            }
        },
        watch: {
            comments(newComments, oldComments) {
                if(newComments === oldComments) {
                    let me = this;
                    me.$nextTick(function() {
                        let container = me.$el.parentElement;
                        container.scrollTop = container.scrollHeight;
                    });
                }
            }
        },
        props: ["comments"],
        components: {
            "photo-comment": PhotoComment
        }
    }
</script>
