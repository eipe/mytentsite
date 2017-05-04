<template>
    <div class="page page-allow-overflow">
        <section class="section">
            <div class="content columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Login</h1>
                    <form @submit.prevent="submitForm" action="/login" method="POST">
                        <p class="control">
                            <input id="email" type="email" name="email" v-model="info.email"
                                   value="" class="input" placeholder="E-mail address" required autofocus>
                        </p>
                        <p class="control">
                            <input id="password" type="password" name="password" v-model="info.password"
                                   class="input" placeholder="Password" required>
                        </p>

                        <p class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="remember" v-model="info.remember" class="checkbox"> Remember me
                            </label>
                        </p>

                        <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                            <div class="notification is-danger" v-if="error">
                                <span class="delete" @click.prevent="error = null"></span>
                                {{ error }}
                            </div>
                        </transition>

                        <div class="control is-grouped">
                            <p class="control">
                                <button type="submit" class="button is-primary"
                                        v-bind:class="{ 'is-loading' : isPosting }">Login</button>
                            </p>
                            <p class="control">
                                <router-link to="/password/reset" tag="button" class="button is-link">
                                    Forgot your password?
                                </router-link>
                            </p>
                            <p class="control">
                                <router-link to="/register" tag="button" class="button is-link">
                                    Don't have an account?
                                </router-link>
                            </p>
                        </div>
                        <hr>
                        <h2 class="is-small">Or use one of your existing accounts</h2>
                        <div class="control is-grouped">
                            <p class="control">
                                <button class="button is-info"
                                        @click.prevent="socialLogin('/auth/facebook/', 'facebook')"
                                        v-bind:class="{ 'is-loading' : socialLoginProvider == 'facebook' }">
                                    <span class="icon is-small"><i class="fa fa-facebook"></i></span>
                                    <span>Facebook</span>
                                </button>
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
                socialLoginProvider: "",
                error: null,
                info: {
                    email: "",
                    password: "",
                    remember: false,
                }
            }
        },
        methods: {
            socialLogin(path, provider) {
                let me = this;
                me.error = null;
                me.socialLoginProvider = provider;

                let baseURL = axios.defaults.baseURL;

                axios.defaults.baseURL = "";
                axios.get(path).then(function(success) {
                    me.socialLoginProvider = "";
                }).catch(function(error) {
                    if(typeof error.response !== typeof undefined) {
                        me.error = error.response.data.error;
                    }
                    me.socialLoginProvider = "";
                });
            },
            submitForm() {
                let me = this;
                me.isPosting = true;
                me.error = null;

                this.$auth.login({
                    data: me.info,
                    url: "/login",
                    rememberMe: me.info.remember,
                    redirect: "/user",
                    fetchUser: false,
                    success(response) {
                        me.isPosting = false;
                    },
                    error(error) {
                        me.isPosting = false;
                        if(typeof error.response.data.error !== typeof undefined) {
                            me.error = error.response.data.error;
                        }
                    }
                });
            }
        }
    }
</script>
