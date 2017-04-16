<template>
    <div>
        <section class="section">
            <div class="content columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Reset password</h1>
                    <form method="post" @submit.prevent="submitForm">
                        <p class="controls">
                            <input id="email" type="email" class="input" name="email"
                                   placeholder="E-mail address"
                                   v-model="info.email" required>
                        </p>
                        <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                            <div class="notification is-danger" v-if="error">
                                <span class="delete" @click.prevent="error = null"></span>
                                {{ error }}
                            </div>
                        </transition>
                        <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                            <div class="notification is-success" v-if="isSuccess">
                                We sent an email with link to reset your password to <u>{{ info.email }}</u>.
                                This link is active for 30 minutes.
                            </div>
                        </transition>
                        <div class="control is-grouped">
                            <p class="control" v-if="!isSuccess">
                                <button type="submit" class="button is-primary"
                                        v-bind:class="{ 'is-loading' : isPosting }">Send password reset link</button>
                            </p>
                            <p class="control">
                                <button @click="back" class="button is-link">{{ backButtonText }}</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                isPosting: false,
                isSuccess: false,
                error: null,
                info: {
                    email: null
                }
            }
        },
        computed: {
            backButtonText() {
                if(this.isSuccess) {
                    return "Go back to login";
                } else {
                    return "Back";
                }
            }
        },
        methods: {
            back() {
                this.isSuccess = false;
                this.error = null;
                this.info.email = null;
                this.$router.go(-1);
            },
            submitForm() {
                let me = this;
                me.isSuccess = false;
                me.isPosting = true;
                me.error = null;

                axios.post("/password/email", me.info).then(function(success) {
                    me.isSuccess = true;
                    me.isPosting = false;
                }).catch(function(error) {
                    if(typeof error.response !== typeof undefined) {
                        me.error = error.response.data.message;
                    }
                    me.isPosting = false;
                });
            }
        }
    }
</script>