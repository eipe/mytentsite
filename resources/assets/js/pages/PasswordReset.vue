<template>
    <div class="container content">
        <section class="section">
            <h1>{{ $t('action.resetPassword')}}</h1>
            <form method="post" @submit.prevent="submitForm">
                <p class="control has-icons-left">
                    <input id="email"
                           type="email"
                           class="input"
                           name="email"
                           :placeholder="$t('authentication.email')"
                           v-model="info.email"
                           ref="email" autofocus required>
                    <span class="icon is-small is-left">
                        <i class="fa fa-envelope"></i>
                    </span>
                </p>
                <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                    <div class="notification is-danger" v-if="error">
                        <span class="delete" @click.prevent="error = null"></span>
                        {{ error }}
                    </div>
                </transition>
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                    <div class="notification is-success" v-if="isSuccess">
                        {{ $t('resetPasswordInfo', [info.email])}}
                    </div>
                </transition>
                <div class="field is-grouped">
                    <p class="control" v-if="!isSuccess">
                        <button type="submit" class="button is-primary"
                                v-bind:class="{ 'is-loading' : isPosting }">{{ $t('sendPasswordResetLink')}}</button>
                    </p>
                    <p class="control">
                        <button @click="back" class="button is-text">{{ backButtonText }}</button>
                    </p>
                </div>
            </form>
        </section>
    </div>
</template>
<script>
    export default {
        i18n: {
            messages: {
                en: {
                    goBackToLogin: 'Go back to login',
                    resetPasswordInfo: 'We sent an email with link to reset your password to <u>{0}</u>.\n' +
                    'This link is active for 30 minutes.',
                    sendPasswordResetLink: 'Send password reset link',
                },
                no: {
                    goBackToLogin: 'Tilbake til innlogging',
                    resetPasswordInfo: 'Vi har sendt deg en epost med lenke for å nullstille passord til <u>{0}.\n' +
                    'Denne lenken er aktiv i 30 minutter.',
                    sendPasswordResetLink: 'Send lenke for å nullstille passord',
                }
            }
        },
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
                    return this.$t('goBackToLogin');
                } else {
                    return this.$t('action.back');
                }
            }
        },
        activated() {
            this.$refs.email.focus();
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

                Vue.axios.post("/password/email", me.info).then(() => {
                    me.isSuccess = true;
                    me.isPosting = false;
                }).catch((error) => {
                    if(typeof error.response !== typeof undefined) {
                        me.error = error.response.data.message;
                    }
                    me.isPosting = false;
                });
            }
        }
    }
</script>