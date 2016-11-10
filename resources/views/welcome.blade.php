<!DOCTYPE html>
<html>
    <head>
        <title>Mytentsite - a collaborative tent collective</title>
        <link href="{{ asset('/css/vendor.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('/css/app_normalize.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <nav class="top-bar">
            <ul id="menu" class="menu align-center" data-page-default="map">
                <li data-page="photo">
                    <a href="#/photo" title="Upload photo of your tentsite"><i class="fa fa-camera"></i></a>
                </li>
                <li data-page="map">
                    <a href="#/map" title="Get a view of shared tentsites on the map">
                        <i class="fa fa-map-o"></i>
                    </a>
                </li>
                <li data-page="wall">
                    <a href="#/wall" title="Explore shared tentsites by viewing our image wall">
                        <i class="fa fa-th"></i>
                    </a>
                </li>
                <li data-page="info">
                    <a href="#/info" title="Information about this service"><i class="fa fa-info"></i></a>
                </li>
            </ul>
        </nav>
        <div id="content">
            <div class="page is-hidden" id="photo">
                <div id="photo-frame">
                    <label for="photo-file" class="button" data-text="I have a stored photo to upload!">
                        I have a stored photo to upload!
                    </label>
                    <input type="file" id="photo-file" class="show-for-sr">
                </div>
                <div id="photo-controllers" class="input-group is-hidden">
                    <span class="input-group-label" title="Caption"><i class="fa fa-font"></i></span>
                    <input type="text" id="photo-caption" class="input-group-field"
                           title="Caption" placeholder="Caption" />
                    <div class="input-group-button">
                        <button id="photo-location" class="button secondary" title="Location of tentsite">
                            <i class="fa fa-map-marker"></i>
                        </button><button id="photo-cancel" class="button alert" title="Cancel">
                            <i class="fa fa-remove"></i>
                        </button><button id="photo-store" class="button success" title="Share this tentsite">
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="page is-hidden" id="map"></div>
            <div class="page is-hidden" id="wall">
                <div id="wall-fullscreen" class="wall-image-container reveal" data-reveal data-close-on-click="true">
                    <div class="wall-image-controllers">
                        <i class="wall-image-view-map wall-image-enlarged fa fa-map-marker"
                           title="View image on map"></i>
                        <i class="wall-image-close fa fa-times" data-close title="Close"></i>
                    </div>
                    <img src="">
                </div>
                <div id="wall-images">
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                    <div class="wall-image-container"></div>
                </div>
                <br />
                <div class="row">
                    <button class="button float-center is-hidden" id="wall-load-more">Load more tentsites</button>
                </div>
                <div class="row"><br /><br /></div>
            </div>
            <div class="page is-hidden" id="info">
                <div class="row"><br /></div>
                <div class="row">
                    <div class="medium-6 large-centered columns text-center">
                        <h4>The story and vision of mytentsite</h4>
                        <hr />
                        <br />
                        <div class="row">
                            <div class="large-4 columns">
                                <i class="fa fa-5x fa-picture-o"></i><br />
                                Photo
                            </div>
                            <div class="large-4 columns">
                                <i class="fa fa-5x fa-map-marker"></i><br />
                                Location
                            </div>
                            <div class="large-4 columns">
                                <i class="fa fa-5x fa-font"></i><br />
                                Caption
                            </div>
                        </div>
                        <br />
                        <hr />
                        <br />
                        <p>As we have focused on developing the story has not yet been written in words.</p>
                        <p>If you have the time and desire to help us, we would appreciate that a lot, and we think
                            this project deserves a good description. So please, do not hesitate to contact us if you
                            want to make your contribution to this project.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="reveal" id="app-modal" data-reveal>
            <h4></h4>
            <p></p>
            <button class="close-button" data-close aria-label="Close" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <button data-close aria-label="Close" class="secondary button hollow">OK</button>
        </div>
        <script src="{{ asset('/js/vendor.js') }}"></script>
        <script src="{{ asset('/js/app.js') }}"></script>
    </body>
</html>
