<template>
    <div v-if="comments.length > 0">
        <slot v-for="comment in comments">
            <photo-comment
                :tentSiteId="comment.tent_site_id"
                :commentId="comment.id"
                :commentBy="comment.user_id"
                :commentByName="comment.user_name"
                :createdAt="comment.created_at"
                :comment="comment.comment"/>
        </slot>
        <div class="is-clearfix"></div>
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
