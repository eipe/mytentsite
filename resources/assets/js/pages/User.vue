<template>
    <div class="page page-allow-overflow">
        <div class="row"><br /></div>
        <div class="row">
            <div class="large-8 large-centered columns text-center">
                <h4>Hi, {{ this.$store.state.user.name }}!</h4>
                <hr>
                <slot v-if="socialLogin.valid">
                    <p>You signed up through {{ socialLogin.provider }},<br>
                    and the last authentication was {{ socialLogin.time }}.</p>
                    <a href="/auth/sign_out" class="button">Sign out</a>
                </slot>
                <slot v-else>
                    <a class="button" href="/logout" @click="logout()">Logout</a>
                </slot>
            </div>
        </div>
        <div v-if="tentSites">
            <div class="row background-light-gray">
                <div class="large-8 large-centered columns text-center">
                    <br>
                    <h5><a data-toggle="user-contributions" title="Click to toggle view of contributions">
                        Your contributions ({{ tentSites.length }})</a></h5>
                    <br>
                </div>
                <div id="user-contributions" class="photo-wall large-8 large-centered">
                    <template v-for="tentSite in tentSites">
                        <photo :id="tentSite.id"
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
                    <br><br>
                </div>
            </div>
            <br><br>
        </div>
        <footer-component/>
    </div>
</template>
<script>

    import Photo from '../components/Photo.vue';
    import Footer from '../components/Footer.vue';
    import { mapGetters } from 'vuex'

    var $apiToken = document.getElementById('api_token'),
        apiToken = $apiToken.innerHTML.toString();

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
                $.ajax({
                    url: '/api/user/?api_token=' +  apiToken,
                    success: function(response) {
                        if(parseInt(response.code) === 200) {
                            if(typeof response.data !== typeof undefined) {
                                me.$store.commit('setUser', response.data);
                            }
                        }
                    }
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
