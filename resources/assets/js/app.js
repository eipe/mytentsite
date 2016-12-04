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
        var fncCallbackOnFetchedSites,
            config = {
                apiUrl : "/api/tentsites",
                storageFolder : "/storage/images/tentsites/"
            };

        function hasExtendedCacheLifeTime() {
            var intLastFetchTime = localStorage.getItem("Sites.lastFetchTime");
            if(!intLastFetchTime) {
                return true;
            } else {
                // Cache lifetime is one hour
                return ((getTime() - intLastFetchTime) > 3600000);
            }
        }

        function fetchSites() {
            if(hasExtendedCacheLifeTime()) {
                localStorage.removeItem("Sites.all");
            }

            var storedPhotos = localStorage.getItem("Sites.all");

            if(storedPhotos) {
                fncCallbackOnFetchedSites(JSON.parse(localStorage.getItem("Sites.all")));
            } else {
                $.ajax({
                    url: config.apiUrl,
                    success: function(response) {
                        var tentSites = [];
                        if(parseInt(response.code) === 200 && typeof response.data !== typeof undefined) {
                            $.each(response.data, function(key, photo) {
                                tentSites.push({
                                    id: photo["id"],
                                    reported_by: photo["reported_by"],
                                    lat: photo["latitude"],
                                    lng: photo["longitude"],
                                    location_name: photo["location_name"],
                                    created_time: photo["created_time"],
                                    likes: photo["likes"],
                                    img_location: config.storageFolder + photo["img_location"],
                                    external_id: photo["external_id"],
                                    thumbnail: config.storageFolder + photo["img_location"],
                                    caption: photo["caption"],
                                    created_at: photo["created_at"],
                                    updated_at: photo["updated_at"]
                                });
                            });
                        }

                        if(!storedPhotos) {
                            storedPhotos = [];
                        } else {
                            storedPhotos = JSON.parse(storedPhotos);
                        }

                        storedPhotos = storedPhotos.concat(tentSites);

                        localStorage.setItem("Sites.lastFetchTime", getTime());
                        localStorage.setItem("Sites.all", JSON.stringify(storedPhotos));
                        fncCallbackOnFetchedSites(tentSites);
                    }, error: function(error) {
                        console.log(error);
                    }
                });
            }
        }

        return {
            "onFetchedSites" : function(fncCallback) {
                fncCallbackOnFetchedSites = fncCallback;
                fetchSites();
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
                    template = '<img src="{img_location}" /></a><p>{caption}</p>';
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
                    view.displayError("Could not detect your location", event.message);
                });

                sites.onFetchedSites(function(sites) {
                    placeSites(sites);
                });
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
            $wallPhotoContainer = $("#wall-photos"),
            $wallFullscreen = $("#wall-fullscreen"),
            $wallLoadMore = $("#wall-load-more"),
            $preLoadedContainers = $wallPhotoContainer.find(".wall-photo-container"),
            index = $preLoadedContainers.index(),
            loaded = false;

        function createPhotoWall(sites) {
            $.each(sites, function(key, photo) {
                var $container = $preLoadedContainers.eq(index);
                index++;
                if($container.length === 0) {
                    $container = $("<div>").addClass("wall-photo-container").appendTo($wallPhotoContainer);
                }

                $container.attr("data-photo-id", photo.id)
                    .attr("data-photo-latitude", photo.lat)
                    .attr("data-photo-longitude", photo.lng)
                    .attr("data-photo-location", photo.img_location)
                    .attr("data-photo-caption", photo.caption);

                $container.append($("<img>").attr("src", photo.img_location));
                $container.append('<div class="wall-photo-controllers is-hidden">' +
                    '<i class="wall-photo-view-map fa fa-map-marker" title="View photo on map"></i>' +
                    '<i class="wall-photo-enlarge fa fa-arrows-alt fa-3x" title="View enlarged photo"></i>' +
                    '</div>');
            });

            $(".wall-photo-view-map").on("click", function(e) {
                e.stopPropagation();
                var $photoContainer = $(this).closest(".wall-photo-container");
                if($photoContainer.hasClass("reveal")) {
                    $photoContainer.foundation("close");
                }
                view.changePage("map");
                map.updateView($photoContainer.data("photo-latitude"), $photoContainer.data("photo-longitude"), 9);
            });

            $(".wall-photo-enlarge").on("click", function(e) {
                e.stopPropagation();
                var $photoContainer = $(this).closest(".wall-photo-container");
                $wallFullscreen.attr("data-photo-latitude", $photoContainer.data("photo-latitude")).
                attr("data-photo-longitude", $photoContainer.data("photo-longitude"));
                $wallFullscreen.find("img").attr("src", $photoContainer.data("photo-location"));
                $wallFullscreen.find("p").text($photoContainer.data("photo-caption"));
                $wallFullscreen.foundation("open");
            });

            // Support for non-mouse interaction
            $(document).on("click", ".wall-photo-container", function(e) {
                e.stopPropagation();
                $(this).find(".wall-photo-controllers").toggleClass("is-hidden");
            });
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
                    sites.onFetchedSites(function(sites) {
                        createPhotoWall(sites);
                        $wallLoadMore.removeClass("is-hidden");
                    });

                    $wallLoadMore.on("click", function() {
                        sites.onFetchedSites(function(sites) {
                            createPhotoWall(sites);
                        });
                        $wall.animate({scrollTop: $wall.prop("scrollHeight") - 80}, 1000);
                    });
                }
            },
            destruct: function() {
            }
        }
    }

    function Photo() {
        var $frame, $preview, $uploader, $uploaderLabel, $caption,
            $rotate, $cancel, $store, $location,
            location = null, loaded = false,
            options = {
                target: "/api/tentsites",
                cropperSettings: {
                    aspectRatio: 4 / 3,
                    zoomable: false,
                    viewMode: 1,
                    dragMode: "none",
                    toggleDragModeOnDblclick: false
                }
            };

        function storePhoto(callback) {
            if(typeof $uploader.prop("files") !== typeof undefined) {
                var photoData = new FormData();
                photoData.append("latitude", location.latitude);
                photoData.append("longitude", location.longitude);
                photoData.append("caption", $caption.val());
                photoData.append("photo", $preview.cropper("getCroppedCanvas").toDataURL());
                $.ajax({
                    url: options.target,
                    method: "POST",
                    data: photoData,
                    cache : false,
                    contentType : false,
                    processData : false
                }).success(function() {
                    callback(200, "Photo successfully uploaded");
                }).error(function(response) {
                    var error = '',
                        errors = $.parseJSON(response.responseText);
                    if(typeof errors.data.form_validations !== typeof undefined) {
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

        function togglePhotoControllers() {
            $("#photo-controllers").toggleClass("is-hidden");
            $uploaderLabel.toggleClass("is-hidden");
        }

        function clearPhotoDetails() {
            $caption.val("");
            $uploader.val("");
            $frame.find("img").attr("src", "");
            $preview.cropper("destroy");
            clearLocation();
        }

        function clearLocation() {
            $location.removeData("location");
            location = null;
        }

        function setLocation(lat, lng, accuracy) {
            $location.data("location", true).attr("title", "Location found");
            location = {
                latitude: lat,
                longitude: lng,
                accuracy: accuracy
            };
        }

        function setupListeners() {
            $frame = $("#photo-frame");
            $preview = $("#photo-preview");
            $caption = $("#photo-caption");
            $location = $("#photo-location");
            $cancel = $("#photo-cancel");
            $store = $("#photo-store");
            $uploader = $("#photo-file");
            $uploaderLabel = $('label[for="photo-file"]');
            $rotate = $("#photo-rotate");

            $rotate.click(function() {
                $preview.cropper("rotate", 90);
            });

            $cancel.on("click", function() {
                clearPhotoDetails();
                togglePhotoControllers();
            });

            $store.on("click", function() {
                if(!location) {
                    return false;
                }
                $preview.cropper("disable");
                storePhoto(function(responseCode, responseText) {
                    $preview.cropper("enable");
                    if(responseCode === 200) {
                        $uploaderLabel
                            .addClass("success")
                            .text("Photo successfully uploaded. Click to upload a new photo");
                        clearPhotoDetails();
                        togglePhotoControllers();
                    } else {
                        view.displayError("Upload of photo was not successful", responseText);
                    }
                });
            });

            $uploaderLabel.on("click", function() {
                $(this).removeClass("alert success").text($(this).data("text"));
            });

            $uploader.on("change", function() {
                var file = $(this).prop("files")[0];

                if(typeof file !== typeof undefined) {
                    EXIF.getData(file, function() {
                        if(typeof EXIF.getTag(this, 'GPSLatitude') === typeof undefined) {
                            // Throw error as this photo does not have required EXIF data
                            view.displayError(
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

                        setLocation(lat, lng);
                        togglePhotoControllers();

                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $preview.attr("src", e.target.result);
                            $preview.cropper(options.cropperSettings);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        }

        return {
            initialize: function() {
                if(!loaded) {
                    loaded = true;
                    setupListeners();
                }
            },
            destruct: function() {
            }
        }
    }

    function View() {
        var $currentPage, $currentPageContent, currentPageName, $modal;

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
        }

        function getPageContentObject(page) {
            return $("#content").find('#' + page);
        }

        function findPageByName(pageName) {
            return $("#menu").find('li[data-page="'+pageName+'"]');
        }

        return {
            initialize: function() {
                sites = new Sites();
                photo = new Photo();
                map = new Map();
                wall = new Wall();
                $modal = $("#app-modal");
                var $menu = $("#menu");
                var tmpPageName = localStorage.getItem("App.View.currentPage");
                if(!tmpPageName) {
                    tmpPageName =  $menu.data("page-default");
                }
                setCurrentPage(findPageByName(tmpPageName), tmpPageName);

                $menu.on("click", "li", function() {
                    var $page = $(this);
                    setCurrentPage($page, $page.data("page"));
                });
            },
            changePage: function(pageName) {
                setCurrentPage(findPageByName(pageName), pageName);
            },
            displayError: function(title, error) {
                $modal.find("h4").text(title);
                $modal.find("p").html(error);
                $modal.foundation("open");
            }
        }
    }

    view = new View();
    view.initialize();

    $(document).foundation();
})(jQuery);