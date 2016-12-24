@extends('layouts.app')
@section('content')
    <div class="page page-allow-overflow">
        <div class="row">
            <div class="columns large-4 large-centered text-center" style="margin-top: 40px;">
                <img src="/images/mytentsite.svg" />
                <h4>mytentsite</h4>
                <p>We are currently working on the tent site, <br>and are looking forward to invite you to join!</p>
                <form action="//site.us14.list-manage.com/subscribe/post?u=1f675764e3102d26f82b8f7ab&amp;id=4ed8a8d5b8"
                      method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                      class="validate" target="_blank" novalidate>
                    <label>Subscribe to our mailing list and we will inform you when we launch!</label>
                    <input type="email" value="" name="EMAIL" class="email"
                           id="mce-EMAIL" placeholder="email address" required>
                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true">
                        <input type="text" name="b_1f675764e3102d26f82b8f7ab_4ed8a8d5b8" tabindex="-1" value="">
                    </div>
                    <div>
                        <input type="submit" value="Subscribe" name="subscribe"
                               id="mc-embedded-subscribe" class="button">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection