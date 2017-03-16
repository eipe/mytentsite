<template>
    <div class="page page-allow-overflow">
        <section class="section">
            <div class="content columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Login</h1>
                    <form @submit.prevent="submitForm" action="/login" method="POST">
                        <p class="control">
                            <input id="email" type="email" name="email" v-model="email"
                                   value="" class="input" placeholder="E-mail address" required autofocus>
                        </p>
                        <p class="control">
                            <input id="password" type="password" name="password" v-model="password"
                                   class="input" placeholder="Password" required>
                        </p>

                        <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                            <div class="notification is-danger" v-if="error">
                                <span class="delete button" @click.prevent="error = null"></span>
                                {{ error }}
                            </div>
                        </transition>

                        <p class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="remember" v-model="remember" class="checkbox"> Remember me
                            </label>
                        </p>

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
                                <a class="button is-info" href="/auth/facebook">
                                    <span class="icon is-small"><i class="fa fa-facebook"></i></span>
                                    <span>Facebook</span>
                                </a>
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
                email: '',
                password: '',
                remember: false,
                isPosting: false,
                error: null
            }
        },
        methods: {
            submitForm() {
                let me = this,
                    form = new FormData();
                me.isPosting = true;
                me.error = null;

                form.append('email', me.email);
                form.append('password', me.password);

                axios.post('/login', form).then(function(success) {
                    me.$store.dispatch('loginWithToken', success.data.token);
                    me.isPosting = false;
                }).catch(function(error) {
                    if(typeof error.response !== typeof undefined) {
                        me.error = error.response.data.error;
                    }
                    me.isPosting = false;
                });
            }
        }
    }
</script>
