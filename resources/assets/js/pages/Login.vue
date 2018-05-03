<template>
    <div class="container content">
        <section class="section">
            <h1>{{ $t('action.logIn')}}</h1>
            <form @submit.prevent="submitForm" action="/login" method="POST">
                <p class="control has-icons-left">
                    <input id="email"
                           type="email"
                           name="email"
                           v-model="info.email"
                           value=""
                           class="input"
                           :placeholder="$t('authentication.email')"
                           ref="email"
                           required autofocus>
                    <span class="icon is-small is-left">
                        <i class="fa fa-envelope"></i>
                    </span>
                </p>
                <p class="control has-icons-left">
                    <input id="password"
                           type="password"
                           name="password"
                           v-model="info.password"
                           class="input"
                           :placeholder="$t('authentication.password')" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-lock"></i>
                    </span>
                </p>

                <p class="control">
                    <label class="checkbox">
                        <input type="checkbox"
                               name="remember"
                               v-model="info.remember"
                               class="checkbox"> {{ $t('authentication.rememberMe')}}
                    </label>
                </p>

                <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                    <div class="notification is-danger" v-if="error">
                        <span class="delete" @click.prevent="error = null"></span>
                        {{ error }}
                    </div>
                </transition>

                <div class="field is-grouped">
                    <p class="control">
                        <button type="submit" class="button is-primary"
                                v-bind:class="{ 'is-loading' : isPosting }">{{ $t('action.logIn')}}</button>
                    </p>
                    <p class="control">
                        <router-link to="/password/reset" tag="a" class="button is-text">
                            {{ $t('authentication.forgotPasswordQuestion')}}
                        </router-link>
                    </p>
                </div>
                <router-link to="/register" tag="a" class="button is-white">
                    {{ $t('authentication.dontHaveAnAccountQuestion')}}
                </router-link>
                <div v-if="socialLoginEnabled">
                    <hr>
                    <h2 class="is-small">{{ $t('authentication.useExistingAccount')}}</h2>
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
                </div>
            </form>
        </section>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                isPosting: false,
                socialLoginEnabled: false,
                socialLoginProvider: "",
                error: null,
                info: {
                    email: "",
                    password: "",
                    remember: false,
                }
            }
        },
        activated() {
            this.$refs.email.focus();
        },
        methods: {
            socialLogin(path, provider) {
                let me = this;
                me.error = null;
                me.socialLoginProvider = provider;

                this.$auth.oauth2({
                    provider: provider,
                    success: function handleSuccess(response) {
                    },
                    error: function handleError(error) {
                    }
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
                    fetchUser: true,
                    success(response) {
                        me.isPosting = false;
                        me.info.email = "";
                        me.info.password = "";
                        me.info.remember = false;
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
