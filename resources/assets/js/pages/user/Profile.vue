<template>
    <div>
        <section class="hero">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">Hi, {{ $auth.user().name }}!</h1>
                    <hr>
                    <slot v-if="socialLogin.valid">
                        <p>You signed up through {{ socialLogin.provider }},<br>
                            and the last authentication was {{ socialLogin.time }}.</p>
                        <a href="/auth/sign_out" class="button is-info">Sign out</a>
                    </slot>
                    <slot v-else>
                        <a class="button is-info" href="/logout" @click.prevent="logout">Logout</a>
                    </slot>
                </div>
            </div>
        </section>
    </div>
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
        },
        created() {
            if(!this.$auth.user().name) {
                this.$auth.fetch();
            }
        }
    }
</script>
