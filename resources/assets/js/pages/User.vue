<template>
    <div class="page page-allow-overflow">
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
                        <a class="button is-info" href="/logout" @click="logout()">Logout</a>
                    </slot>
                </div>
            </div>
        </section>
        <div v-if="tentSites">
            <section class="hero is-light">
                <div class="hero-body">
                    <div class="container">
                        <h2 class="title">Your contributions ({{ tentSites.length }})</h2>
                        <div class="columns is-multiline is-mobile">
                            <template v-for="tentSite in tentSites">
                                <photo class="column is-2" :id="tentSite.id"
                                       :img_location="tentSite.img_location"
                                       :thumbnail="tentSite.thumbnail"
                                       :lat="tentSite.lat"
                                       :lng="tentSite.lng"
                                       :caption="tentSite.caption"
                                       :reported_by="tentSite.reported_by"
                                       :created_at="tentSite.created_at"
                                       :likes="tentSite.likes" :approved="tentSite.approved" :showDetails="true">
                                </photo>
                            </template>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer-component/>
    </div>
</template>
<script>

    import Photo from '../components/Photo.vue';
    import Footer from '../components/Footer.vue';
    import { mapGetters } from 'vuex'

    export default {
        data() {
            return {
                socialLogin: {
                    valid: {
                        type: Boolean
                    },
                    provider: {
                        type: String
                    },
                    time: {
                        type: String
                    }
                }
            }
        },
        computed: {
            ...mapGetters({tentSites: 'getUserTentSites'})
        },
        methods: {
            loadUserData() {
                var me = this;
                axios.get('/user/').then(function(response) {
                    me.$store.commit('setUser', response.data.data);
                }).catch(function(response) {
                    console.log(response);
                });
            },
            logout(event) {
                event.preventDefault();
                //document.getElementById('logout-form').submit();
                //<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                //{{ csrf_field()
                //</form>
            }
        },
        created() {
            this.loadUserData();
        },
        components: {
            Photo,
            'footer-component': Footer
        }
    }
</script>
