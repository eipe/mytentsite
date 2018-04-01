<template>
    <div>
        <h1>Tent sites</h1>
        <p>{{ tentSites.length }} waiting for approval</p>
        <table class="table is-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Caption</th>
                    <th>Created</th>
                    <th>Reported by</th>
                    <th width="50">Preview</th>
                    <th>Map</th>
                    <th>Handle</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tentSite in tentSites">
                    <td>{{ tentSite.id }}</td>
                    <td>{{ tentSite.caption }}</td>
                    <td>{{ tentSite.created_at }}</td>
                    <td>{{ tentSite.reported_by_name }}</td>
                    <td><img :src="tentSite.thumbnail_location" /></td>
                    <td>
                        <button class="button is-outlined" title="View on Google Map" @click.prevent="viewOnMap(tentSite)">
                            <span class="icon is-small">
                                <i class="fa fa-map-o"></i>
                            </span>
                        </button>
                    </td>
                    <td>
                        <button class="button is-success is-outlined" title="Approve" @click.prevent="approve(tentSite)">
                            <span class="icon is-small"><i class="fa fa-thumbs-up"></i></span>
                        </button>
                        <button class="button is-danger is-outlined" title="Deny" @click.prevent="deny(tentSite)">
                            <span class="icon is-small"><i class="fa fa-thumbs-down"></i></span>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Caption</th>
                    <th>Created</th>
                    <th>Reported by</th>
                    <th>Preview</th>
                    <th>Map</th>
                    <th>Handle</th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
<script>

    import Photo from "../../components/Photo.vue"

    export default {
        name: "AdminTentSites",
        data() {
            return {
                tentSites: []
            }
        },
        created() {
            let me = this;
            Vue.axios.get("unapproved").then(function(response) {
                response.data.data.forEach(function(value) {
                    me.tentSites.push(value);
                });
            }).catch(function() {
            });
        },
        methods: {
            removeTentSite(id) {
                let index = this.tentSites.findIndex(function(photo) {
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
                Vue.axios.post("/admin/approve/" + id).then(function() {
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
                Vue.axios.post("/admin/deny/" + id).then(function() {
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
            Photo
        }
    }
</script>
