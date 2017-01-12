/**
 * Created by Eivind Røe <eivindroe@gmail.com> on 06.08.2016.
 */
(function($) {
    'use strict';
    var view, photo, map, wall, sites;

    function getTime() {
        if(typeof Date.now !== typeof undefined) {
            return Date.now();
        } else {
            // Fallback for IE8
            return new Date().getTime();
        }
    }

    function Sites() {
        var tentSites = [],
            nextPageUrl,
            loadedAll = false,
            config = {
                apiUrl : "/api/tentsites"
            };

        function hasExtendedCacheLifeTime() {
            var intLastFetchTime = localStorage.getItem("Sites.lastFetchTime");
            if(!intLastFetchTime) {
                return true;
            } else {
                // Cache lifetime is one minute
                return ((getTime() - intLastFetchTime) > 60000);
            }
        }

        function getCachedPhotosAsArray() {
            var storedPhotos = getCachedPhotos();
            if(storedPhotos) {
                return JSON.parse(storedPhotos);
            }
            return [];
        }

        function getCachedPhotos() {
            return localStorage.getItem("Sites.all");
        }

        function setSiteApiUrl(strUrl) {
            localStorage.setItem("Sites.nextPageUrl", strUrl);
        }

        function getSiteApiUrl() {
            if(hasExtendedCacheLifeTime()) {
                localStorage.removeItem("Sites.nextPageUrl");
            }
            var cachedNextUrl = localStorage.getItem("Sites.nextPageUrl");
            if(cachedNextUrl) {
                if(cachedNextUrl === "lastPage") {
                    loadedAll = true;
                    return null;
                } else {
                    return cachedNextUrl;
                }
            } else {
                return config.apiUrl;
            }
        }

        function fetchSites() {
            var apiUrl = getSiteApiUrl();
            if(!apiUrl) {
                return false;
            }

            $.ajax({
                url: apiUrl,
                success: function(response) {
                    if(parseInt(response.code) === 200) {
                        nextPageUrl = response.data.next_page_url;
                        if(!nextPageUrl) {
                            setSiteApiUrl("lastPage");
                            loadedAll = true;
                        } else {
                            setSiteApiUrl(nextPageUrl);
                        }

                        if(typeof response.data.data !== typeof undefined && response.data.data.length > 0) {
                            var newPhotos = [];
                            $.each(response.data.data, function (key, photo) {
                                var tentSite = {
                                    id: photo["id"],
                                    reported_by: photo["reported_by"],
                                    lat: photo["latitude"],
                                    lng: photo["longitude"],
                                    likes: photo["likes"],
                                    img_location: photo["img_location"],
                                    thumbnail: photo["thumbnail_location"],
                                    caption: photo["caption"],
                                    created_at: photo["created_at"],
                                    updated_at: photo["updated_at"],
                                    approved: photo["approved"]
                                };
                                tentSites.push(tentSite);
                                newPhotos.push(tentSite);
                            });

                            localStorage.setItem("Sites.lastFetchTime", getTime());
                            localStorage.setItem("Sites.all", JSON.stringify(getCachedPhotosAsArray().concat(newPhotos)));
                        }
                    }
                }, error: function(error) {
                    console.log(error);
                }
            });
        }

        return {
            "loadMore": function() {
                if(!loadedAll) {
                    fetchSites();
                }
            },
            "getTentSites": function() {
                if(tentSites.length === 0) {
                    if(hasExtendedCacheLifeTime()) {
                        localStorage.removeItem("Sites.all");
                    }

                    var cachedPhotos = getCachedPhotosAsArray();
                    if(cachedPhotos.length > 0) {
                        tentSites = cachedPhotos;
                    } else {
                        fetchSites();
                    }
                }
                return tentSites;
            },
            "hasLoadedAll": function() {
                return loadedAll;
            }
        }
    }

    function Map() {
        var loaded = false,
            TentMap,
            locationCircle,
            $map;

        // Configure layers
        var WorldImagery = L.tileLayer(
            "http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
            attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, "+
            "GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"
        }),
        OpenStreetMap = L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            subdomains: ["a","b","c"]
        }),
        Kartverket_Topographic = L.tileLayer(
            "http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=topo2&zoom={z}&x={x}&y={y}", {
            attribution: '<a href="http://www.kartverket.no/">Kartverket</a>',
            transparent: true
        });

        // Configure base maps
        var baseMaps = {
            "World imagery": WorldImagery,
            "Open street map" : OpenStreetMap
        };

        var overlayMaps = {
            "Norway: topographic" : Kartverket_Topographic
        };

        function bindMap() {
            TentMap = L.map("map").setView([63.412222, 10.404722], 4);
        }

        function placeSites(tentSites) {
            var photoLayer = L.photo.cluster().on("click", function (evt) {
                var photo = evt.layer.photo,
                    template = '<img src="{thumbnail}" /></a><p>{caption}</p><p>{reported_by} - {created_at}</p>';
                if (photo.video &&
                    (!!document.createElement("video").canPlayType("video/mp4; codecs=avc1.42E01E,mp4a.40.2"))
                ) {
                    template = '<video autoplay controls poster="{img_location}">' +
                        '<source src="{video}" type="video/mp4"/></video>';
                }
                evt.layer.bindPopup(L.Util.template(template, photo), {
                    className: "leaflet-popup-photo"
                }).openPopup();
            });
            photoLayer.add(tentSites).addTo(TentMap);
        }

        function markLocation(latitude, longitude, accuracy) {
            if(locationCircle) {
                TentMap.removeLayer(locationCircle);
            }
            locationCircle = L.circle([latitude, longitude], accuracy, {
                color: "red",
                fillColor: "#f03",
                fillOpacity: 0.5
            }).addTo(TentMap);
        }

        return {
            initialize: function() {
                if(loaded === true) {
                    TentMap.invalidateSize();
                    return;
                }
                loaded = true;
                bindMap();
                OpenStreetMap.addTo(TentMap);
                L.control.layers(baseMaps, overlayMaps).addTo(TentMap);

                $map = $("#map");

                // Add view position button
                L.easyButton({
                    position: "topleft",
                    states: [{
                        icon: "fa-crosshairs",
                        title: "View my position",
                        onClick: function(button, map) {
                            map.locate();
                        }
                    }]
                }).addTo(TentMap);

                TentMap.on("locationfound", function(event) {
                    markLocation(event.latlng.lat, event.latlng.lng, event.accuracy);
                    TentMap.setView(event.latlng, 10);
                });

                TentMap.on("locationerror", function(event) {
                    view.displayModalMessage("Could not detect your location", event.message);
                });

                placeSites(sites.getTentSites());
            },
            updateView: function(latitude, longitude, zoom) {
                if(!latitude || !longitude) {
                    return false;
                }
                if(!loaded) {
                    this.initialize();
                }
                TentMap.setView(
                    new L.LatLng(latitude, longitude),
                    zoom,
                    {animate: true, duration: 0.2, noMoveStart: true, easyLinearity: 0.25}
                );
            },
            destruct: function() {

            }
        }
    }

    function Wall() {
        var $wall = $("#wall"),
            $wallFullscreen = $("#wall-fullscreen"),
            $wallFullscreenPhoto = $wallFullscreen.find("img"),
            $wallFullscreenCaption = $("#wall-fullscreen-caption"),
            $wallFullscreenReported = $("#wall-fullscreen-reported"),
            loaded = false;

        Vue.component('photo', Vue.extend({
            template: '<div class="wall-photo-container" ' +
                        ':data-photo-id="id"' +
                        ':data-photo-latitude="lat"' +
                        ':data-photo-longitude="lng"' +
                        ':data-photo-caption="caption"' +
                        ':data-photo-reported-by="reported_by"' +
                        ':data-photo-created-at="created_at"' +
                        ':data-photo-location="img_location"><img :src="thumbnail" :data-src="img_location" />' +
                        '<div class="wall-photo-controllers is-hidden"> ' +
                        '<i class="wall-photo-view-map fa fa-map-marker" title="View photo on map"></i> ' +
                        '</div></div>',
            props: {
                id: {
                    type: Number,
                    required: true
                },
                img_location: {
                    type: String,
                    required: true
                },
                thumbnail: {
                    type: String,
                    required: true
                },
                lat: {
                    type: Number,
                    required: true
                },
                lng: {
                    type: Number,
                    required: true
                },
                caption: {
                    type: String,
                    required: true
                },
                created_at: {
                    type: String,
                    required: true
                },
                reported_by: {
                    type: String,
                    required: true
                }
            },
            data: function () {
                return {};
            }
        }));

        function createPhotoWall() {
            new Vue({
                el: '#wall-content',
                data: {
                    photos: sites.getTentSites(),
                    hasMore: !sites.hasLoadedAll()
                }
            });

            $(document).on("click", ".wall-photo-view-map", function(e) {
                e.stopPropagation();
                var $photoContainer = $(this).closest(".wall-photo-container");
                if($photoContainer.hasClass("reveal")) {
                    $photoContainer.foundation("close");
                }
                view.changePage("map");
                map.updateView($photoContainer.data("photo-latitude"), $photoContainer.data("photo-longitude"), 9);
            });

            $wall.on("click", ".wall-photo-container", function(e) {
                e.stopPropagation();
                var $photoContainer = $(this);
                $wallFullscreen
                    .data("photo-latitude", $photoContainer.data("photo-latitude"))
                    .data("photo-longitude", $photoContainer.data("photo-longitude"));
                $wallFullscreenPhoto.attr("src", $photoContainer.data("photo-location"));
                $wallFullscreenCaption.text($photoContainer.data("photo-caption"));
                $wallFullscreenReported.text(
                    $photoContainer.data("photo-reported-by") + " - " + $photoContainer.data("photo-created-at")
                );
                $wallFullscreen.foundation("open");
            });

            // Support for non-mouse interaction
            $(document).on("mouseover", ".wall-photo-container", function(e) {
                e.stopPropagation();
                $(this).find(".wall-photo-controllers").removeClass("is-hidden");
            });
            $(document).on("mouseout", ".wall-photo-container", function(e) {
                e.stopPropagation();
                $(this).find(".wall-photo-controllers").addClass("is-hidden");
            });
        }

        return {
            initialize: function() {
                if(loaded === false) {
                    loaded = true;
                    createPhotoWall();

                    $("#wall img").unveil();

                    $(document).on("click", "#wall-load-more", function() {
                        sites.loadMore();
                        $wall.animate({scrollTop: $wall.prop("scrollHeight") - 80}, 1000);
                    });
                }
            },
            destruct: function() {
            }
        }
    }

    function Photo() {
        var $frame, $preview, $previewLoading, $uploader, $uploaderLabel, uploadEvent, $caption,
            $rotate, $store, $cancel, loaded = false, $controllersContainer,
            photoExifData = {
                location: {
                    latitude: null,
                    longitude: null
                },
                taken_date: null
            },
            options = {
                target: "/api/tentsites",
                cropItSettings: {
                    exportZoom: 1,
                    imageBackground: true,
                    imageBackgroundBorderWidth: 1,
                    onImageLoading: function() {

                    },
                    onImageLoaded: function() {
                        $previewLoading.addClass("is-hidden");
                    },
                    onImageError: function() {
                        $previewLoading.addClass("is-hidden");
                        view.displayModalMessage("Could not load photo", "Please try again, or try another photo");
                    }
                },
                cropItExportOptions: {
                    type: "image/jpeg",
                    quality: 1,
                    originalSize: true
                }
            };

        var $apiToken = $("#api_token");
        if($apiToken.length > 0) {
            options.target += "?api_token=" +  $apiToken.text();
        }

        function storePhoto(callback) {
            if(typeof $uploader.prop("files") !== typeof undefined) {
                var photoData = new FormData();
                photoData.append("latitude", photoExifData.location.latitude);
                photoData.append("longitude", photoExifData.location.longitude);
                photoData.append("caption", $caption.val());
                photoData.append("photo", $frame.cropit("export", options.cropItExportOptions));
                if(photoExifData.taken_date.length > 0) {
                    photoData.append("taken_date", photoExifData.taken_date);
                }
                photoControllerNext();
                uploadEvent = $.ajax({
                    url: options.target,
                    method: "POST",
                    data: photoData,
                    cache : false,
                    contentType : false,
                    processData : false
                }).success(function() {
                    view.closeModalMessage();
                    callback(200, "Photo successfully uploaded");
                }).error(function(response) {
                    // Handle canceled upload
                    if(parseInt(response.readyState) === 0 && response.statusText === "abort") {
                        return;
                    }
                    photoControllerPrevious();
                    var error = '',
                        errors = $.parseJSON(response.responseText);
                    if(typeof errors.error !== typeof undefined) {
                        error = errors.error;
                    }
                    else if(typeof errors.data.form_validations !== typeof undefined) {
                        $.each(errors.data.form_validations, function(field, fieldError) {
                            error += fieldError + "<br>";
                        });
                    }
                    callback(400, error);
                });
            } else {
                callback(400, "Missing file to upload");
            }
        }

        function toggleUploaderLabel() {
            $uploaderLabel.toggleClass("is-hidden");
        }

        function togglePhotoControllers() {
            $controllersContainer.toggleClass("is-hidden");
        }

        function resetPhotoControllers() {
            $controllersContainer
                .find("span[data-step=" + $controllersContainer.data("current-step") + "]")
                .addClass("is-hidden");
            $controllersContainer.find("span[data-step=1]").removeClass("is-hidden");
            setPhotoControllerCurrentStep(1);
        }

        function getPhotoControllerCurrentStep()
        {
            return $controllersContainer.data("current-step");
        }

        function setPhotoControllerCurrentStep(intStep) {
            $controllersContainer.data("current-step", intStep);
        }

        function photoControllerNext() {
            var currentStep = getPhotoControllerCurrentStep();
            if($controllersContainer.data("max-step") === currentStep) {
                return false;
            }
            $controllersContainer.find("span[data-step=" + currentStep + "]").toggleClass("is-hidden");
            currentStep++;
            $controllersContainer.find("span[data-step=" + currentStep + "]").toggleClass("is-hidden");
            setPhotoControllerCurrentStep(currentStep);

            // Disable photo editing when user has moved away from step 1
            if(currentStep > 1) {
                $frame.cropit("disable");
            }
        }

        function photoControllerPrevious() {
            var currentStep = getPhotoControllerCurrentStep();
            if(currentStep === 1) {
                return false;
            }
            $controllersContainer.find("span[data-step=" + currentStep + "]").toggleClass("is-hidden");
            currentStep--;
            $controllersContainer.find("span[data-step=" + currentStep + "]").toggleClass("is-hidden");
            setPhotoControllerCurrentStep(currentStep);

            // Enable photo editing if user is back on step 1
            if(currentStep === 1) {
                $frame.cropit("reenable");
            }
        }

        function clearPhotoDetails() {
            $caption.val("");
            $uploader.val("");
            $frame.find("img").attr("src", "");
            $preview.removeClass("cropit-image-loaded");
            clearPhotoExifData();
            togglePhotoControllers();
            resetPhotoControllers();
            toggleUploaderLabel();
        }

        function clearPhotoExifData() {
            photoExifData.location.latitude = null;
            photoExifData.location.longitude = null;
            photoExifData.taken_date = null;
        }

        function setLocationData(lat, lng) {
            photoExifData.location.latitude = lat;
            photoExifData.location.longitude = lng;
        }

        function setupListeners() {
            $frame = $("#photo-frame");
            $preview = $("#photo-preview");
            $previewLoading = $("#photo-preview-loading");
            $caption = $("#photo-caption");
            $store = $("#photo-store");
            $uploader = $("#photo-file");
            $uploaderLabel = $('label[for="photo-file"]');
            $rotate = $("#photo-rotate");
            $controllersContainer = $("#photo-controllers");
            $cancel = $("#photo-cancel-upload");

            var maxStep = $controllersContainer.data("current-step");
            $controllersContainer.find("*[data-step]").each(function() {
                var $stepContainer = $(this),
                    step = $stepContainer.data("step");
                if(step > maxStep) {
                    maxStep = step;
                }
            });
            $controllersContainer.data("max-step", maxStep);

            $(".photo-controllers-next").on("click", function() {
                photoControllerNext();
            });

            $(".photo-controllers-previous").on("click", function() {
                photoControllerPrevious();
            });

            $rotate.click(function() {
                $frame.cropit("rotateCW");
            });

            $(".photo-cancel").on("click", function() {
                clearPhotoDetails();
            });

            $store.on("click", function() {
                if(!location) {
                    return false;
                }
                storePhoto(function(responseCode, responseText) {
                    if(responseCode === 200) {
                        $uploaderLabel
                            .addClass("success")
                            .text("Tent site successfully uploaded. Click to share another tent site!");
                        clearPhotoDetails();
                    } else {
                        view.displayModalMessage("Upload of photo was not successful", responseText);
                    }
                });
            });

            $cancel.on("click", function() {
                uploadEvent.abort();
                photoControllerPrevious();
            });

            $frame.cropit(options.cropItSettings);

            $uploaderLabel.on("click", function() {
                $(this).removeClass("alert success").text($(this).data("text"));
            });

            $uploader.on("change", function() {
                toggleUploaderLabel();
                $previewLoading.removeClass("is-hidden");

                var file = $(this).prop("files")[0];

                if(typeof file !== typeof undefined) {
                    EXIF.getData(file, function() {
                        if(typeof EXIF.getTag(this, 'GPSLatitude') === typeof undefined) {
                            toggleUploaderLabel();
                            $previewLoading.addClass("is-hidden");
                            // Throw error as this photo does not have required EXIF data
                            view.displayModalMessage(
                                "Photo does not contain location data",
                                "We can not accept photos without location data as they are impossible to place on " +
                                "the map, which indeed is the whole concept of this service. <br /><br />" +
                                "Please try a new photo"
                            );
                            $uploaderLabel
                                .addClass("alert")
                                .text("Photo does not contain location data, try a new one!");
                            return false;
                        }

                        var exifData = EXIF.getAllTags(this),
                            lat = exifData.GPSLatitude,
                            lng = exifData.GPSLongitude;

                        // Convert coordinates to WGS84 decimal
                        var latRef = exifData.GPSLatitudeRef || "N";
                        var lngRef = exifData.GPSLongitudeRef || "W";
                        lat = (lat[0] + lat[1]/60 + lat[2]/3600) * (latRef == "N" ? 1 : -1);
                        lng = (lng[0] + lng[1]/60 + lng[2]/3600) * (lngRef == "W" ? -1 : 1);

                        setLocationData(lat, lng);

                        if(typeof exifData.DateTime !== typeof undefined) {
                            photoExifData.taken_date = exifData.DateTime;
                        }

                        togglePhotoControllers();
                    });
                }
            });
        }

        function hasActiveUpload() {
            return ($uploader.val());
        }

        return {
            initialize: function() {
                if(!loaded) {
                    loaded = true;
                    setupListeners();
                }

                window.onbeforeunload = function() {
                    if(hasActiveUpload() &&
                        !confirm("You have a pending photo upload - want to finish that before leaving this page?")) {
                        return false;
                    }
                };
            },
            destruct: function() {
                if(hasActiveUpload() === false) {
                    window.onbeforeunload = null;
                }
            }
        }
    }

    function View() {
        var currentPage, $currentPage, $currentPageContent, currentPageName, $modal, $menu, pages = [];

        function toggleCurrentPage() {
            if(typeof $currentPage !== typeof undefined) {
                $currentPage.toggleClass("is-active");
                $currentPageContent.toggleClass("is-hidden");
            }
        }

        function destructCurrentPage() {
            toggleCurrentPage();
            if(typeof currentPageName !== typeof undefined) {
                if(currentPageName == "photo") {
                    photo.destruct();
                } else if(currentPageName == "map") {
                    map.destruct();
                } else if(currentPageName == "wall") {
                    wall.destruct();
                }
            }
        }

        function initializeCurrentPage() {
            toggleCurrentPage();
            if(typeof currentPageName !== typeof undefined) {
                if(currentPageName == "photo") {
                    photo.initialize();
                } else if(currentPageName == "map") {
                    map.initialize();
                } else if(currentPageName == "wall") {
                    wall.initialize();
                }
            }
        }

        function setCurrentPage($page, pageName) {
            if(pageName === currentPageName) {
                return;
            }
            destructCurrentPage();
            localStorage.setItem("App.View.currentPage", pageName);
            $currentPage = $page;
            $currentPageContent = getPageContentObject(pageName);
            currentPageName = pageName;
            initializeCurrentPage();
            document.location.href = "#/" + pageName;
        }

        function getPageContentObject(page) {
            return $("#content").find('#' + page);
        }

        function findPageByName(pageName) {
            return $("#menu").find('li[data-page="'+pageName+'"]');
        }

        function getPageFromUrl() {
            var url = window.location.href;
            return url.substring(url.indexOf("#")+2);
        }

        return {
            initialize: function() {
                sites = new Sites();
                photo = new Photo();
                map = new Map();
                wall = new Wall();
                $modal = $("#app-modal");
                $menu = $("#menu");
                $menu.children().each(function() {
                    pages.push($(this).data("page"));
                });

                // Check if page is delegated through url and that given page exists
                var urlPageIndex = pages.indexOf(getPageFromUrl());

                if(urlPageIndex > -1) {
                    currentPage = pages[urlPageIndex];
                }

                // If not defined through url we check for cached page, which will be defined if user has visited before
                if(!currentPage) {
                    var cachedPage = localStorage.getItem("App.View.currentPage");

                    if(cachedPage && pages.indexOf(cachedPage) >= 0) {
                        currentPage = cachedPage;
                    }
                }

                // If still no defined page we use the default page defined in application
                if(!currentPage) {
                    currentPage = $menu.data("page-default");
                }

                setCurrentPage(findPageByName(currentPage), currentPage);

                $(window).on("popstate", function() {
                    var pageIndex = pages.indexOf(getPageFromUrl());
                    if(pageIndex > -1) {
                        var changeToPage = pages[pageIndex];
                        setCurrentPage(findPageByName(changeToPage), changeToPage);
                    }
                });

                $menu.on("click", "li", function() {
                    var $page = $(this);
                    setCurrentPage($page, $page.data("page"));
                });
            },
            changePage: function(pageName) {
                setCurrentPage(findPageByName(pageName), pageName);
            },
            displayModalMessage: function(title, message) {
                $modal.find("h4").text(title);
                $modal.find("p").html(message);
                $modal.foundation("open");
            },
            closeModalMessage: function() {
                $modal.foundation("close");
            }
        }
    }

    view = new View();
    view.initialize();

    $(document).foundation();
})(jQuery);