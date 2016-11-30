@if(!Auth::check())
    <div class="row">
        <div id="photo-frame" class="column medium-centered text-center">
            <div>
                <label for="photo-file" class="button" data-text="I have a stored photo to upload">
                    I have a stored photo to upload
                </label>
                <div>
                    <img id="photo-preview" src="" />
                </div>
                <input type="file" id="photo-file" class="show-for-sr" />
            </div>
            <div id="photo-controllers" class="input-group is-hidden">
                <span class="input-group-label" title="Caption"><i class="fa fa-font"></i></span>
                <input type="text" id="photo-caption" class="input-group-field"
                       title="Caption" placeholder="Caption" />
                <div class="input-group-button">
                    <button id="photo-location" class="button secondary show-for-sr" title="Location of tent site">
                        <i class="fa fa-map-marker"></i>
                    </button><button id="photo-rotate" class="button secondary" title="Rotate clockwise">
                        <i class="fa fa-rotate-right"></i>
                    </button><button id="photo-cancel" class="button alert" title="Cancel">
                        <i class="fa fa-remove"></i>
                    </button><button id="photo-store" class="button success" title="Share this tent site">
                        <i class="fa fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@else
    @include('auth.sign_up')
@endif