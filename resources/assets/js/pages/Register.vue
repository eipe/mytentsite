<template>
    <div class="container content">
        <section class="section">
            <h1>{{ $t('createNewAccount')}}</h1>
            <form method="post" @submit.prevent="submitForm">
                <p class="control has-icons-left">
                    <input id="name"
                           type="text"
                           name="name"
                           class="input"
                           :placeholder="$t('profile.name')"
                           v-model="info.name"
                           ref="name" required autofocus>
                    <span class="icon is-small is-left">
                        <i class="fa fa-user"></i>
                    </span>
                </p>
                <p class="control has-icons-left">
                    <input id="email"
                           type="email"
                           name="email"
                           class="input"
                           :placeholder="$t('authentication.email')"
                           v-model="info.email" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-envelope"></i>
                    </span>
                </p>
                <p class="control has-icons-left">
                    <input id="password"
                           type="password"
                           name="password"
                           class="input"
                           :placeholder="$t('authentication.password')"
                           v-model="info.password" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-lock"></i>
                    </span>
                </p>
                <p class="control has-icons-left">
                    <input id="password-confirm"
                           type="password"
                           name="password_confirmation"
                           class="input"
                           :placeholder="$t('authentication.confirmPassword')"
                           v-model="info.password_confirmation" required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-lock"></i>
                    </span>
                </p>

                <p class="control">
                    <label for="subscribe" class="label">
                        <input id="subscribe" type="checkbox"
                               name="subscribe" class="checkbox"
                               v-model="info.subscribe"> {{ $t('authentication.subscribe')}}
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
                                v-bind:class="{ 'is-loading' : isPosting }">{{ $t('createNewAccount')}}</button>
                    </p>
                    <p class="control">
                        <button @click="back" class="button is-text">{{ $t('action.back')}}</button>
                    </p>
                </div>
            </form>
        </section>
    </div>
</template>
<script>
    export default {
        name: 'Register',
        i18n: {
            messages: {
                en: {
                    createNewAccount: 'Create a new account'
                },
                no: {
                    createNewAccount: 'Opprett ny konto'
                }
            }
        },
        data() {
            return {
                isPosting: false,
                error: null,
                info: {
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    subscribe: false
                }
            }
        },
        activated() {
            this.$refs.name.focus();
        },
        methods: {
            back() {
                this.$router.go(-1);
            },
            submitForm() {
                let me = this;
                me.isPosting = true;
                me.error = null;

                me.$auth.register({
                    data: me.info,
                    url: "/register",
                    redirect: "/user",
                    fetchUser: true,
                    success: function(response) {
                        me.isPosting = false;
                    },
                    error: function(error) {
                        me.isPosting = false;
                        if(typeof error.response !== typeof undefined) {
                            me.error = error.response.data.message;
                        }
                    }
                });
            }
        }
    }
</script>
