<template>
    <div>
        <section class="section">
            <div class="content columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Create a new account</h1>
                    <form method="POST" action="/register">
                        <!--{{ csrf_field() }}-->

                        <label for="name" class="label">Name</label>
                        <p class="controls">
                            <input id="name" type="text" name="name" value="" class="input"
                                   placeholder="Name" required autofocus>
                            <!--<input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>-->
                            <!--@if ($errors->has('name'))-->
                            <!--<span class="round alert label">{{ $errors->first('name') }}</span>-->
                            <!--@endif-->
                        </p>

                        <label for="email" class="label">E-mail address</label>
                        <p class="controls">
                            <input id="email" type="email" name="email" value="" class="input"
                                   placeholder="E-mail address" required>
                            <!--<input id="email" type="email" name="email" value="{{ old('email') }}" required>-->
                            <!--@if ($errors->has('email'))-->
                            <!--<span class="round alert label">{{ $errors->first('email') }}</span>-->
                            <!--@endif-->
                        </p>

                        <label for="password" class="label">Password</label>
                        <p class="controls">
                            <input id="password" type="password" name="password" class="input"
                                   placeholder="Password" required>
                            <!--@if ($errors->has('password'))-->
                            <!--<span class="round alert label">{{ $errors->first('password') }}</span>-->
                            <!--@endif-->
                        </p>

                        <label for="password-confirm" class="label">Confirm password</label>
                        <p class="controls">
                            <input id="password-confirm" type="password"
                                   name="password_confirmation" class="input" placeholder="Confirm password" required>
                        </p>

                        <label for="subscribe" class="label">
                            <input id="subscribe" type="checkbox"
                                   name="subscribe" class="checkbox"> Subscribe to our mailinglist
                        </label>

                        <div class="control is-grouped">
                            <p class="control">
                                <button type="submit" class="button is-primary">Create a new account</button>
                            </p>
                            <p class="control">
                                <a href="/auth/facebook" class="button is-info">
                                    <span class="icon is-small"><i class="fa fa-facebook"></i></span>
                                    <span>Sign up using Facebook</span>
                                </a>
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
            }
        },
        methods: {
            back() {
                this.$router.go(-1);
            }
        }
    }
</script>
