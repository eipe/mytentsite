<template>
    <div>
        <section class="section">
            <div class="content columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Create a new account</h1>
                    <form method="post" @submit.prevent="submitForm">
                        <p class="controls">
                            <input id="name" type="text" name="name" class="input"
                                   placeholder="Name"
                                   v-model="info.name" ref="name" required autofocus>
                        </p>
                        <p class="controls">
                            <input id="email" type="email" name="email" class="input"
                                   placeholder="E-mail address"
                                   v-model="info.email" required>
                        </p>
                        <p class="controls">
                            <input id="password" type="password" name="password" class="input"
                                   placeholder="Password"
                                   v-model="info.password" required>
                        </p>
                        <p class="controls">
                            <input id="password-confirm" type="password"
                                   name="password_confirmation" class="input"
                                   placeholder="Confirm password"
                                   v-model="info.password_confirmation" required>
                        </p>

                        <p class="controls">
                            <label for="subscribe" class="label">
                                <input id="subscribe" type="checkbox"
                                       name="subscribe" class="checkbox"
                                       v-model="info.subscribe"> Subscribe to our mailinglist
                            </label>
                        </p>

                        <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                            <div class="notification is-danger" v-if="error">
                                <span class="delete" @click.prevent="error = null"></span>
                                {{ error }}
                            </div>
                        </transition>

                        <div class="field is-grouped">
                            <p class="control">
                                <button type="submit" class="button is-primary"
                                        v-bind:class="{ 'is-loading' : isPosting }">Create a new account</button>
                            </p>
                            <p class="control">
                                <button @click="back" class="button is-link">Back</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        name: 'Register',
        data() {
            return {
                isPosting: false,
                error: null,
                info: {
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    subscribe: false
                }
            }
        },
        activated() {
            this.$refs.name.focus();
        },
        methods: {
            back() {
                this.$router.go(-1);
            },
            submitForm() {
                let me = this;
                me.isPosting = true;
                me.error = null;

                Vue.axios.post("/register", me.info).then(function(success) {
                    me.isPosting = false;
                    me.$router.push({ path: "/user" });
                }).catch(function(error) {
                    if(typeof error.response !== typeof undefined) {
                        me.error = error.response.data.message;
                    }
                    me.isPosting = false;
                });
            }
        }
    }
</script>
