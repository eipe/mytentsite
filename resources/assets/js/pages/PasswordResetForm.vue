<template>
    <div class="container content">
        <section class="section">
            <h1>Reset password</h1>
            <form method="post" @submit.prevent="submitForm">
                <p class="control has-icons-left">
                    <input id="password" type="password" class="input" name="password"
                           placeholder="Password"
                           v-model="info.password" ref="password" autofocus required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-lock"></i>
                    </span>
                </p>

                <p class="control has-icons-left">
                    <input id="password-confirm" type="password" class="input" name="password_confirmation"
                           placeholder="Confirm password"
                           v-model="info.password_confirmation" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-lock"></i>
                    </span>
                </p>

                <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                    <div class="notification is-danger" v-if="errors.length > 0">
                        <span class="delete" @click.prevent="errors = []"></span>
                        <div v-for="error in errors">{{ error }}</div>
                    </div>
                </transition>

                <div class="field is-grouped">
                    <p class="control">
                        <button type="submit" class="button is-primary"
                                v-bind:class="{ 'is-loading' : isPosting }">Reset password</button>
                    </p>
                    <p class="control">
                        <router-link to="/login" tag="button" class="button is-text">
                            Back to login
                        </router-link>
                    </p>
                </div>
            </form>
        </section>
    </div>
</template>
<script>
    export default {
        name: 'Password-reset-form',
        data() {
            return {
                isPosting: false,
                errors: [],
                info: {
                    password: null,
                    password_confirmation: null,
                    token: this.$route.query.token
                }
            }
        },
        activated() {
            this.$refs.password.focus();
        },
        methods: {
            submitForm() {
                let me = this;
                me.isPosting = true;
                me.errors = [];

                Vue.axios.post("/password/reset", me.info).then(function(success) {
                    me.isPosting = false;
                    me.errors = [];
                    me.$router.push("/info");
                }).catch(function(error) {
                    if(typeof error.response.data.error !== typeof undefined) {
                        error.response.data.error.forEach(function(text) {
                            me.errors.push(text);
                        });
                    }
                    if(typeof error.message !== typeof undefined) {
                        me.errors.push(error.message);
                    }
                    me.isPosting = false;
                });
            }
        }
    }
</script>
