module.exports = function Share() {
    let $frame, $preview, $previewLoading, $uploader, $uploaderLabel, uploadEvent, $caption,
        $rotate, $store, $cancel, loaded = false, $controllersContainer,
        photoExifData = {
            location: {
                latitude: null,
                longitude: null
            },
            taken_date: null
        },
        options = {
            cropItSettings: {
                allowDragNDrop: false,
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
                    alert("Could not load photo: " + "Please try again, or try another photo");
                }
            },
            cropItExportOptions: {
                type: "image/jpeg",
                quality: 1,
                originalSize: true
            }
        };

    function storePhoto(callback) {
        if(typeof $uploader.prop("files") !== typeof undefined) {
            let photoData = new FormData();
            photoData.append("latitude", photoExifData.location.latitude);
            photoData.append("longitude", photoExifData.location.longitude);
            photoData.append("caption", $caption.val());
            photoData.append("photo", $frame.cropit("export", options.cropItExportOptions));
            if(photoExifData.taken_date.length > 0) {
                photoData.append("taken_date", photoExifData.taken_date);
            }
            photoControllerNext();
            uploadEvent = axios.post('/tentsites', photoData).then(function() {
                alert("Photo successfully uploaded");
                callback(200, "Photo successfully uploaded");
            }).catch(function(error) {
                // Handle canceled upload
                if(parseInt(error.response.readyState) === 0 && error.response.statusText === "abort") {
                    return;
                }
                photoControllerPrevious();
                let errorText = '';

                if(typeof error.response.data.form_validations !== typeof undefined) {
                    error.response.data.form_validations.forEach(function(field, fieldError) {
                        errorText += fieldError + "<br>";
                    });
                }
                callback(400, errorText);
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
        let currentStep = getPhotoControllerCurrentStep();
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
        let currentStep = getPhotoControllerCurrentStep();
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

        let maxStep = $controllersContainer.data("current-step");
        $controllersContainer.find("*[data-step]").each(function() {
            let $stepContainer = $(this),
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
                        .addClass("is-success")
                        .text("Tent site successfully uploaded. Click to share another tent site!");
                    clearPhotoDetails();
                } else {
                    alert('Upload of photo was not successful: ' + responseText);
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

            let file = $(this).prop("files")[0];

            if(typeof file !== typeof undefined) {
                EXIF.getData(file, function() {
                    if(typeof EXIF.getTag(this, 'GPSLatitude') === typeof undefined) {
                        toggleUploaderLabel();
                        $previewLoading.addClass("is-hidden");
                        // Throw error as this photo does not have required EXIF data
                        alert('Photo does not contain location data: ' + "We can not accept photos without location data as they are impossible to place on " +
                            "the map, which indeed is the whole concept of this service. <br /><br />" +
                            "Please try a new photo");
                        $uploaderLabel
                            .addClass("is-danger")
                            .text("Photo does not contain location data, try a new one!");
                        return false;
                    }

                    let exifData = EXIF.getAllTags(this),
                        lat = exifData.GPSLatitude,
                        lng = exifData.GPSLongitude;

                    // Convert coordinates to WGS84 decimal
                    let latRef = exifData.GPSLatitudeRef || "N",
                        lngRef = exifData.GPSLongitudeRef || "W";
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
        }
    }
}