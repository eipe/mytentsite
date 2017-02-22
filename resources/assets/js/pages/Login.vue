<template>
    <div class="page page-allow-overflow">
        <section class="section">
            <div class="content columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Login</h1>
                        <!--<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">-->
                    <form action="/login" method="POST">
                        <!--{{ csrf_field() }}-->
                        <label class="label" for="email">E-Mail Address</label>
                        <p class="control">
                        <!--<input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>-->
                            <input id="email" type="email" name="email" value="" class="input" required autofocus>
                        </p>
                        <!--@if ($errors->has('email'))-->
                        <!--<span class="round alert label">{{ $errors->first('email') }}</span>-->
                        <!--@endif-->

                        <label class="label" for="password">Password</label>
                        <p class="control">
                            <input id="password" type="password" name="password" class="input" required>
                        </p>
                        <!--@if ($errors->has('password'))-->
                        <!--<span class="round alert label">{{ $errors->first('password') }}</span>-->
                        <!--@endif-->

                        <p class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="remember" class="checkbox"> Remember me
                            </label>
                        </p>

                        <div class="control is-grouped">
                            <p class="control">
                                <button type="submit" class="button is-primary">Login</button>
                            </p>
                            <p class="control">
                                <router-link to="/password/reset" tag="button" class="button is-link">
                                    Forgot your password?
                                </router-link>
                            </p>
                            <p class="control">
                                <router-link to="/register" tag="button" class="button is-link">
                                    Don't have an account?
                                </router-link>
                            </p>
                        </div>
                        <hr>
                        <h2 class="is-small">Or use one of your existing accounts</h2>
                        <div class="control is-grouped">
                            <p class="control">
                                <a class="button is-info" href="/auth/facebook">
                                    <i class="fa fa-facebook"></i>&nbsp;&nbsp;Facebook
                                </a>
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
        }
    }
</script>
