<template @focus="focus">
    <div>
        <form @submit.prevent="submitComment">
            <div class="control is-grouped">
                <p class="control is-expanded has-icon has-icon-right">
                    <input class="input" ref="comment" v-model="comment" type="text"
                           placeholder="Write a comment" v-bind:class="{ 'is-danger' : hasErrors }">
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
</template>
<script>
    export default {
        name: 'PhotoCommentForm',
        data() {
            return {
                comment: '',
                isPostingComment: false,
                errors: []
            }
        },
        props: {
            id: {
                required: true
            },
            focus: false
        },
        computed: {
            hasErrors() {
                return (typeof this.errors['comment'] !== typeof undefined);
            }
        },
        methods: {
            submitComment() {
                let me = this;
                me.isPostingComment = true;
                axios.post('/comments/' + this.id, {
                    comment: this.comment
                }).then(function(response) {
                    me.isPostingComment = false;
                    me.comment = '';
                    me.$store.dispatch('addCommentOnPhoto', response.data);
                }).catch(function(error) {
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
        },
        components: {
        }
    }
</script>
