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
                                   v-model="info.name" required autofocus>
                            <span class="round alert label" v-if="errors.name">{{ errors.name }}</span>
                        </p>
                        <p class="controls">
                            <input id="email" type="email" name="email" class="input"
                                   placeholder="E-mail address"
                                   v-model="info.email" required>
                            <span class="round alert label" v-if="errors.email">{{ errors.email }}</span>
                        </p>
                        <p class="controls">
                            <input id="password" type="password" name="password" class="input"
                                   placeholder="Password"
                                   v-model="info.password" required>
                            <span class="round alert label" v-if="errors.password">{{ errors.password }}</span>
                        </p>
                        <p class="controls">
                            <input id="password-confirm" type="password"
                                   name="password_confirmation" class="input"
                                   placeholder="Confirm password"
                                   v-model="info.password_confirmation" required>
                            <span class="round alert label" v-if="errors.password_confirmation">
                                {{ errors.password_confirmation }}
                            </span>
                        </p>

                        <p class="controls">
                            <label for="subscribe" class="label">
                                <input id="subscribe" type="checkbox"
                                       name="subscribe" class="checkbox"
                                       v-model="info.subscribe"> Subscribe to our mailinglist
                            </label>
                        </p>

                        <transition enter-active-class="animated shake" leave-active-class="animated fadeOut">
                            <div class="notification is-danger" v-if="errors.message">
                                <span class="delete" @click.prevent="errors.message = null"></span>
                                {{ errors.message }}
                            </div>
                        </transition>

                        <div class="control is-grouped">
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
                errors: {},
                info: {
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    subscribe: false
                }
            }
        },
        methods: {
            back() {
                this.$router.go(-1);
            },
            submitForm() {
                let me = this;
                me.isPosting = true;
                me.errors = {};

                axios.post('/register', me.info).then(function(success) {
                    me.isPosting = false;
                }).catch(function(error) {
                    if(typeof error.response.data.error !== typeof undefined) {
                        me.errors = error.response.data.error;
                    }
                    if(typeof error.message !== typeof undefined) {
                        me.errors.message = error.message;
                    }
                    me.isPosting = false;
                });
            }
        }
    }
</script>
