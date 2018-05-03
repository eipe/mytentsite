<template>
    <div>
        <h1>{{ $t('page.dashboard')}}</h1>
        <ul>
            <li>Apdex: {{ apDex }}</li>
            <li>- redirect: {{ redirect }}</li>
            <li>- namelookup: {{ nameLookup }}</li>
            <li>- connection: {{ connection }}</li>
            <li>- handshake: {{ handshake }}</li>
            <li>- response: {{ response }}</li>
            <li>- total: {{ total }}</li>
        </ul>
    </div>
</template>
<script>
    export default {
        name: "AdminDashboard",
        data() {
            return {
                apDex: "",
                redirect: "",
                nameLookup: "",
                connection: "",
                handshake: "",
                response: "",
                total: ""
            }
        },
        components: {
        }
    }
</script>
