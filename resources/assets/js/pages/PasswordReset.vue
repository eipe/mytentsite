<template>
    <div class="page page-allow-overflow">
        <div class="content container">
            <div class="columns">
                <div class="column is-half is-offset-one-quarter">
                    <h1>Reset password</h1>
                    <!--@if (session('status'))-->
                    <!--<div class="alert alert-success">-->
                    <!--{{ session('status') }}-->
                    <!--</div>-->
                    <!--@endif-->
                    <!--<div class="columns large-4 large-centered text-left form-group{{ $errors->has('email') ? ' has-error' : '' }}">-->
                    <form method="POST" action="/password/email">
                        <!--{{ csrf_field() }}-->
                        <label for="email" class="label">E-mail address</label>
                        <!--<label for="email" class="col-md-4 control-label">E-mail address</label>-->
                        <p class="controls">
                            <input id="email" type="email" class="input" name="email" value="" required>
                        </p>
                            <!--<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>-->
                            <!--@if ($errors->has('email'))-->
                            <!--<span class="help-block">-->
                            <!--<strong>{{ $errors->first('email') }}</strong>-->
                            <!--</span>-->
                            <!--@endif-->
                        <div class="control is-grouped">
                            <p class="control">
                                <button type="submit" class="button is-primary">Send password reset link</button>
                            </p>
                            <p class="control">
                                <button @click="back" class="button is-link">Back</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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