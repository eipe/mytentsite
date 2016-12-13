@if(Auth::check())
    <div class="row">
        <div id="photo-frame" class="medium-centered text-center">
            <label for="photo-file" class="button" data-text="I want to share a tent site!">
                I want to share a tent site!
            </label>
            <div id="photo-preview" class="cropit-preview small-centered color-gray">
                <div id="photo-preview-loading" class="is-hidden">
                    <br>
                    <i class="fa fa-5x fa-circle-o-notch fa-spin"></i><br>
                    <small>We are preparing your photo, please hold on!</small>
                </div>
            </div>
            <input type="file" id="photo-file" class="cropit-image-input show-for-sr" />
            <div id="photo-controllers" class="is-hidden small-centered color-gray">
                <i id="photo-rotate" title="Rotate clockwise"
                   class="is-clickable fa fa-rotate-right"></i>
                <i class="fa fa-image"></i>
                <input type="range" title="Drag to zoom" min="0" max="1" step="0.1" class="cropit-image-zoom-input" />
                <i class="fa fa-image fa-2x"></i>
                <i id="photo-cancel" title="Cancel"
                   class="is-clickable fa fa-remove"></i>
                <i id="photo-store" title="Share this tent site"
                   class="is-clickable fa fa-check"></i>
                <i id="photo-location" class="fa fa-map-marker show-for-sr" title="Location of tent site"></i>
            </div>
        </div>
    </div>
@else
    @include('auth.sign_up')
@endif