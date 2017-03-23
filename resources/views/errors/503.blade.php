@extends('layouts.app')
@section('content')
    <section class="hero is-fullheight is-info">
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-half is-offset-one-quarter has-text-centered">
                        <img src="/images/192-circle.png" />
                        <h1 class="title">Welcome to mytentsite</h1>
                        <h2 class="subtitle">We are currently working on the tent site, <br>
                            and are looking forward to invite you to join!
                        </h2>
                        <form action="//site.us14.list-manage.com/subscribe/post?u=1f675764e3102d26f82b8f7ab&amp;id=4ed8a8d5b8"
                              method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                              class="validate" target="_blank" novalidate>
                            <h2 class="subtitle">
                                Subscribe to our mailing list and we will inform you when we launch!
                            </h2>
                            <p class="controls">
                            <input type="email" value="" name="EMAIL" class="email input"
                                   id="mce-EMAIL" placeholder="email address" autofocus required>
                            </p>
                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                <input type="text" name="b_1f675764e3102d26f82b8f7ab_4ed8a8d5b8" tabindex="-1" value="">
                            </div>

                            <p class="controls">
                                <br>
                                <input type="submit" value="Subscribe" name="subscribe"
                                       id="mc-embedded-subscribe" class="button is-inverted">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection