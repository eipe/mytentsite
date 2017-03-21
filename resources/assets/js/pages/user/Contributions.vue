<template>
    <div>
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
    </div>
</template>
<script>

    import Photo from '../../components/Photo.vue';
    import { mapGetters } from 'vuex'

    export default {
        name: 'User-contributions',
        data() {
            return {
            }
        },
        computed: {
            ...mapGetters({tentSites: 'getUserTentSites'})
        },
        components: {
            Photo
        }
    }
</script>
