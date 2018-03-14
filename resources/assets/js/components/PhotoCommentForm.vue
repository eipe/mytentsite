<template @focus="focus">
    <form @submit.prevent="submitComment" style="width: 100%">
        <div class="field is-grouped">
            <p class="control is-expanded has-icon has-icon-right">
                <input class="input" ref="comment" v-model="comment" type="text"
                       placeholder="Write a comment" v-bind:class="{ 'is-danger' : errors.comment }" required>
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
</template>
<script>
    export default {
        name: 'PhotoCommentForm',
        data() {
            return {
                comment: '',
                isPostingComment: false,
                errors: {}
            }
        },
        props: {
            tentSite: {
                type: Object,
                required: true
            },
            focus: false
        },
        methods: {
            reset() {
                this.comment = "",
                this.errors = {}
            },
            submitComment() {
                let me = this;
                me.isPostingComment = true;
                Vue.axios.post("/comments/" + me.tentSite.id, {
                    comment: me.comment
                }).then(function(response) {
                    me.isPostingComment = false;
                    me.$store.dispatch("addCommentOnPhoto", { id: me.tentSite.id, comment: response.data.data });
                    me.reset();
                }).catch(function(error) {
                    if(typeof error.response.data.data.form_validations !== typeof undefined) {
                        me.errors = error.response.data.data.form_validations;
                    }
                    me.isPostingComment = false;
                });
            }
        },
        watch: {
            focus(val) {
                if(val === true) {
                    this.$refs.comment.focus();
                }
            }
        }
    }
</script>
