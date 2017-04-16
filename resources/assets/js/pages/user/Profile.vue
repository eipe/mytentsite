<template>
    <div>
        <section class="hero">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">Hi, {{ this.$store.state.user.name }}!</h1>
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
</template>r
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
            loadUserData() {
                var me = this;
                axios.get("/user/").then(function(response) {
                    me.$store.commit("setUser", response.data.data);
                }).catch(function(response) {
                });
            },
            logout() {
                this.$store.dispatch("logout");
            }
        },
        created() {
            this.loadUserData();
        }
    }
</script>
