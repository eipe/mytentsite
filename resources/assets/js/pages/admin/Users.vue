<template>
    <div>
        <h1>Users</h1>
        <table class="table is-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Date registered</th>
                <th>Date updated</th>
                <th>Last active</th>
                <th>Admin</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="user in users">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.created_at }}</td>
                    <td>{{ user.updated_at }}</td>
                    <td>{{ user.last_active }}</td>
                    <td>{{ user.is_admin }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date registered</th>
                    <th>Date updated</th>
                    <th>Last active</th>
                    <th>Admin</th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
<script>
    export default {
        name: "AdminUsers",
        data() {
            return {
                users: []
            }
        },
        created() {
            let me = this;
            Vue.axios.get("users").then(function(response) {
                response.data.data.forEach(function(value) {
                    me.users.push(value);
                });
            }).catch(function() {
            });
        },
        components: {
        }
    }
</script>
