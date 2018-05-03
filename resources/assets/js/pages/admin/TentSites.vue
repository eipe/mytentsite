<template>
    <div>
        <h1>{{ $t('page.tentSites')}}</h1>
        <p>{{ $t('tentSite.numberWaitingForApproval', [tentSites.length])}}</p>
        <table class="table is-striped">
            <thead>
                <tr>
                    <th>{{ $t('misc.id')}}</th>
                    <th>{{ $t('tentSite.caption')}}</th>
                    <th>{{ $t('date.created')}}</th>
                    <th>{{ $t('misc.reportedBy')}}</th>
                    <th width="50">{{ $t('misc.preview')}}</th>
                    <th>{{ $t('misc.map')}}</th>
                    <th>{{ $t('misc.handle')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tentSite in tentSites">
                    <td>{{ tentSite.id }}</td>
                    <td>{{ tentSite.caption }}</td>
                    <td>{{ tentSite.created_at }}</td>
                    <td>{{ tentSite.reported_by_name }}</td>
                    <td>
                        <img :src="tentSite.thumbnail_location"
                             class="is-clickable"
                             @click="openGallery(tentSite)"/>
                    </td>
                    <td>
                        <button class="button is-outlined"
                                :title="$t('misc.viewOn', ['Google Map'])"
                                @click.prevent="viewOnMap(tentSite)">
                            <span class="icon is-small">
                                <i class="fa fa-map-o"></i>
                            </span>
                        </button>
                    </td>
                    <td>
                        <button class="button is-success is-outlined"
                                :title="$t('action.approve')"
                                @click.prevent="approve(tentSite)">
                            <span class="icon is-small"><i class="fa fa-thumbs-up"></i></span>
                        </button>
                        <button class="button is-danger is-outlined"
                                :title="$t('action.deny')"
                                @click.prevent="deny(tentSite)">
                            <span class="icon is-small"><i class="fa fa-thumbs-down"></i></span>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ $t('misc.id')}}</th>
                    <th>{{ $t('tentSite.caption')}}</th>
                    <th>{{ $t('date.created')}}</th>
                    <th>{{ $t('misc.reportedBy')}}</th>
                    <th width="50">{{ $t('misc.preview')}}</th>
                    <th>{{ $t('misc.map')}}</th>
                    <th>{{ $t('misc.handle')}}</th>
                </tr>
            </tfoot>
        </table>
        <photo-gallery :tent-sites="tentSites" :user-actions="false" ref="gallery"></photo-gallery>
    </div>
</template>
<script>

    import Photo from "../../components/Photo.vue"
    import PhotoGallery from "../../components/PhotoGallery.vue"

    export default {
        name: "AdminTentSites",
        data() {
            return {
                tentSites: []
            }
        },
        created() {
            let me = this;
            Vue.axios.get("unapproved").then((response) => {
                response.data.data.forEach((tentSite) => {
                    tentSite['bookmarks'] = [];
                    tentSite['comments'] = [];
                    me.tentSites.push(tentSite);
                });
            }).catch(function() {
            });
        },
        methods: {
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            removeTentSite(id) {
                let index = this.tentSites.findIndex((photo) => {
                    if(photo.id === id) {
                        return true;
                    }
                });

                if(typeof this.tentSites[index] !== typeof undefined) {
                    this.tentSites.splice(index, 1);
                }
            },
            approve(tentSite) {
                let me = this,
                    id = tentSite.id;
                Vue.axios.post("/admin/approve/" + id).then(() => {
                    me.removeTentSite(id);
                }).catch(function(error) {
                    let errorMessage = "Could not approve";
                    if(typeof error.response !== typeof undefined) {
                        errorMessage += "<br>"+error.response.data.message;
                    }
                    me.$store.dispatch("displayError", errorMessage + "<br><br>Please try again");
                });
            },
            deny(tentSite) {
                let me = this,
                    id = tentSite.id;
                Vue.axios.post("/admin/deny/" + id).then(() => {
                    me.removeTentSite(id);
                }).catch(function(error) {
                    let errorMessage = "Could not deny";
                    if(typeof error.response !== typeof undefined) {
                        errorMessage += "<br>"+error.response.data.message;
                    }
                    me.$store.dispatch("displayError", errorMessage + "<br><br>Please try again");
                });
            },
            viewOnMap(tentSite) {
                window.open("https://google.com/maps/?q=" + tentSite.latitude + "," + tentSite.longitude);
            }
        },
        components: {
            Photo, PhotoGallery
        }
    }
</script>
