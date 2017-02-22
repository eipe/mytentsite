<template>
    <div class="page page-allow-overflow">
        <section class="section">
            <div class="content columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Reset password</h1>
                    <!--@if (session('status'))-->
                    <!--<div class="alert alert-success">-->
                    <!--{{ session('status') }}-->
                    <!--</div>-->
                    <!--@endif-->

                    <form method="POST" action="/password/reset">
                        <!--{{ csrf_field() }}-->
                        <!--<input type="hidden" name="token" value="{{ $token }}">-->

                        <!--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">-->
                        <label for="email" class="label">E-Mail address</label>
                        <p class="controls">
                            <input id="email" type="email" name="email" class="input" value="" required autofocus>
                            <!--<input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>-->
                            <!--@if ($errors->has('email'))-->
                            <!--<span class="help-block">-->
                            <!--<strong>{{ $errors->first('email') }}</strong>-->
                            <!--</span>-->
                            <!--@endif-->
                        </p>

                        <!--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">-->
                        <label for="password" class="label">Password</label>
                        <p class="controls">
                            <input id="password" type="password" class="input" name="password" required>
                            <!--@if ($errors->has('password'))-->
                            <!--<span class="help-block">-->
                            <!--<strong>{{ $errors->first('password') }}</strong>-->
                            <!--</span>-->
                            <!--@endif-->
                        </p>

                        <!--<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">-->
                        <label for="password-confirm" class="label">Confirm Password</label>
                        <p class="controls">
                            <input id="password-confirm" type="password" class="input" name="password_confirmation" required>
                            <!--<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>-->
                            <!--@if ($errors->has('password_confirmation'))-->
                            <!--<span class="help-block">-->
                            <!--<strong>{{ $errors->first('password_confirmation') }}</strong>-->
                            <!--</span>-->
                            <!--@endif-->
                        </p>

                        <div class="control is-grouped">
                            <p class="control">
                                <button type="submit" class="button is-primary">Reset password</button>
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
