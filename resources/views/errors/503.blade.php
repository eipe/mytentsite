@extends('layouts.app')
@section('content')
    <div style="margin: 40px auto; max-width: 500px">
        <img src="/images/500.png" />
        <div class="text-center">
            <h4>mytentsite</h4>
            <p>We are currently working on the tent site, <br>and are looking forward to invite you to join!</p>
            <!-- Begin MailChimp Signup Form -->
            <link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet" type="text/css">
            <style type="text/css">
                #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
                /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
            </style>
                <form action="//site.us14.list-manage.com/subscribe/post?u=1f675764e3102d26f82b8f7ab&amp;id=4ed8a8d5b8" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                        <label for="mce-EMAIL">Subscribe to our mailing list and we will inform you when we launch!</label>
                        <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_1f675764e3102d26f82b8f7ab_4ed8a8d5b8" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                    </div>
                </form>


            <!--End mc_embed_signup-->
        </div>
    </div>
@endsection