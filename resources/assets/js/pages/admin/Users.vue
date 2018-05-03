<template>
    <div>
        <h1>{{ $t('page.users')}}</h1>
        <p>{{ $t('numberOfUsers', [users.length])}}</p>
        <table class="table is-striped">
            <thead>
            <tr>
                <th>{{ $t('profile.name')}}</th>
                <th>{{ $t('authentication.email')}}</th>
                <th>{{ $t('date.registered')}}</th>
                <th>{{ $t('date.updated')}}</th>
                <th>{{ $t('date.lastActive')}}</th>
                <th>{{ $t('role.admin')}}</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="user in users">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.created_at }}</td>
                    <td>{{ user.updated_at }}</td>
                    <td>{{ user.last_active }}</td>
                    <td>{{ isAdmin(user) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ $t('profile.name')}}</th>
                    <th>{{ $t('authentication.email')}}</th>
                    <th>{{ $t('date.registered')}}</th>
                    <th>{{ $t('date.updated')}}</th>
                    <th>{{ $t('date.lastActive')}}</th>
                    <th>{{ $t('role.admin')}}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
<script>
    export default {
        name: "AdminUsers",
        i18n: {
            messages: {
                en: {
                    numberOfUsers: 'We currently have {0} users'
                },
                no: {
                    numberOfUsers: 'Vi har for øyeblikket {0} brukere'
                }
            },
        },
        data() {
            return {
                users: []
            }
        },
        created() {
            let me = this;
            Vue.axios.get("users").then((response) => {
                response.data.data.forEach((value) => {
                    me.users.push(value);
                });
            }).catch(function() {
            });
        },
        methods: {
            isAdmin(user) {
                return (user.is_admin ? this.$t('misc.yes') : this.$t('misc.no'));
            }
        },
        components: {
        }
    }
</script>
