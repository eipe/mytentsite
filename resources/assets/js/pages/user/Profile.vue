<template>
    <section class="section">
        <div class="container content">
            <slot v-if="socialLogin.valid">
                <p>You signed up through {{ socialLogin.provider }},<br>
                    and the last authentication was {{ socialLogin.time }}.</p>
                <a href="/auth/sign_out" class="button is-danger">{{ $t('action.signOut')}}</a>
            </slot>
            <slot v-else>
                <a href="/logout" class="button is-danger" @click.prevent="logout">{{ $t('action.logOut')}}</a>
            </slot>
        </div>
    </section>
</template>
<script>
    export default {
        name: "User-profile",
        data() {
            return {
                socialLogin: {
                    valid: false,
                    provider: null,
                    time: null
                }
            }
        },
        methods: {
            logout() {
                this.$auth.logout({
                    url: "/logout",
                    makeRequest: true,
                    success() {
                    },
                    error() {
                    }
                });
            }
        }
    }
</script>
