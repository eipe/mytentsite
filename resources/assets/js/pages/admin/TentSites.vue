<template>
    <div>
        <h1>Tent sites</h1>
        <table class="hover">
            <thead>
            <tr>
                <th width="20">Id</th>
                <th>Caption</th>
                <th width="50">Photo</th>
                <th width="50">Map</th>
                <th width="50">Handle</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="tentSite in tentSites">
                <td>{{ tentSite.id }}</td>
                <td>{{ tentSite.caption }}</td>
                <td><img :src="tentSite.thumbnail_location" /></td>
                <td><i @click="viewOnMap(tentSite.id)" title="View on map" class="fa fa-map-o pointer"></i></td>
                <td>
                    <i class="fa fa-thumbs-up badge success pointer" title="Approve" @click="approve(tentSite.id)"></i>
                    <i class="fa fa-thumbs-down badge alert pointer" title="Deny" @click="deny(tentSite.id)"></i>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>

    import Photo from '../../components/Photo.vue'

    export default {

        data() {
            return {
                tentSites: []
            }
        },
        created() {
            let me = this;
            $.ajax({
                url: "/api/unapproved/?api_token=" + me.$store.state.user.apiToken,
                method: "GET",
                success(response) {
                    $.each(response, function(key, value) {
                        value.thumbnail_location = '/storage/photos/tentsite_thumbnails/' + value.thumbnail_location;
                        me.tentSites.push(value);
                    });
                }, error() {
                }
            });
        },
        methods: {
            approve(id) {
                let me = this;
                $.ajax({
                    url: "/api/admin/approve/" + id + "?api_token=" + me.$store.state.user.apiToken,
                    method: "POST",
                    success() {
                        let index = me.tentSites.data.findIndex(function(photo) {
                            if(photo.id === id) {
                                return true;
                            }
                        });

                        if(typeof me.tentSites.index !== typeof undefined) {
                            delete me.tentSites.index;
                        }
                        console.log("Approval successful");
                    }, error(error) {
                        console.log("Could not approve");
                        console.log(error);
                    }
                });
            },
            deny(id) {
                let me = this;
                $.ajax({
                    url: "/api/admin/deny/" + id + "?api_token=" + me.$store.state.user.apiToken,
                    method: "POST",
                    success() {
                        let index = me.tentSites.data.findIndex(function(photo) {
                            if(photo.id === id) {
                                return true;
                            }
                        });

                        if(typeof me.tentSites.index !== typeof undefined) {
                            delete me.tentSites.index;
                        }
                        console.log("Denied successful");
                    }, error() {
                        console.log("Could not deny");
                    }
                });
            },
            viewOnMap(id) {
                let index = this.tentSites.findIndex(function(photo) {
                    if(photo.id === id) {
                        return true;
                    }
                });

                if(typeof this.tentSites[index] !== typeof undefined) {
                    let tentSite = this.tentSites[index];
                    window.open('https://google.com/maps/?q=' + tentSite.latitude + ',' + tentSite.longitude);
                }
            }
        },
        components: {
            Photo
        }
    }
</script>
