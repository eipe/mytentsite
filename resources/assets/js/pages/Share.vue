<template>
    <div class="has-fixed-header">
        <section class="hero is-fullheight is-primary">
            <div class="hero-head">
                <form @submit.prevent="storePhoto">
                    <div class="columns">
                        <div class="column is-half is-offset-one-quarter has-text-centered" id="photo-frame">
                            <input type="file" ref="photo" @change="photoChanged"
                                   class="cropit-image-input is-hidden" />
                            <div v-show="photoLoaded">
                                <transition-group enter-active-class="animated fadeIn">
                                    <figure key="Preview" class="cropit-preview"
                                            v-show="photoLoaded" title="Drag to adjust">
                                    </figure>
                                    <div class="field has-addons is-mobile" key="First step" v-show="step === 1">
                                        <div class="control">
                                            <div class="button is-primary is-hovered">
                                                <span class="icon">
                                                    <i title="Cancel"
                                                       class="fa fa-trash-o"
                                                       @click="cancel"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control">
                                            <div class="button is-primary is-hovered">
                                                <span class="icon">
                                                    <i title="Rotate clockwise"
                                                       class="fa fa-rotate-right"
                                                       @click="rotate"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control">
                                            <div class="button is-primary is-hovered">
                                                <span class="icon">
                                                    <i class="fa fa-image"
                                                       title="Click to zoom out"
                                                       @click="zoom -= 0.1"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control is-expanded" style="padding: 7px 20px">
                                            <input type="range" min="0" max="1" step="0.1"
                                                   class="cropit-image-zoom-input" style="width: 100%" />
                                        </div>
                                        <div class="control">
                                            <div class="button is-primary is-hovered">
                                                <span class="icon">
                                                    <i class="fa fa-image"
                                                       title="Click to zoom in"
                                                       @click="zoom += 0.1"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control">
                                            <div class="button is-primary is-hovered">
                                                <span class="icon">
                                                    <i class="fa fa-arrow-right"
                                                       title="Proceed"
                                                       @click="goToNextStep"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field has-addons" key="Second step" v-show="step === 2">
                                        <div class="control">
                                            <div class="button is-primary is-hovered">
                                                <span class="icon">
                                                    <i title="Cancel"
                                                       class="fa fa-trash-o"
                                                       @click="cancel"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control">
                                            <div class="button is-primary is-hovered">
                                                <span class="icon">
                                                    <i class="fa fa-arrow-left"
                                                       @click="goToPreviousStep"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control is-expanded">
                                            <input name="caption" title="Caption"
                                                      placeholder="Caption"
                                                      v-model="photo.caption"
                                                      maxlength="255"
                                                      class="input" required>
                                        </div>
                                        <div class="control">
                                            <input type="submit" class="button is-primary is-hovered" value="Share"
                                                   title="Share this tent site">
                                        </div>
                                    </div>
                                    <div key="Third step" v-if="step === 3" class="has-text-centered">
                                        We are uploading your tent site
                                        <i class="fa fa-circle-o-notch fa-spin"></i><br><br>
                                        <button class="button is-danger" @click.prevent="abortStoring">
                                            Cancel upload</button>
                                    </div>
                                </transition-group>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="hero-body">
                <transition enter-active-class="animated fadeIn">
                    <div class="container has-text-centered is-clickable" v-if="photoShareSuccess">
                        <h1 class="title" @click="photoShareSuccess = false">Thank's for your contribution!</h1>
                        <h2 class="subtitle">Click me if you want to start over again</h2>
                    </div>
                </transition>
                <div class="container has-text-centered" v-if="(!photoLoaded || photoLoading) && !photoShareSuccess">
                    <transition enter-active-class="animated fadeIn">
                        <div v-if="ready">
                            <div class="columns">
                                <div class="column"><h2 class="subtitle button is-primary" @click="triggerSelectPhoto">
                                    1. Select photo</h2>
                                </div>
                                <div class="column"><h2 class="subtitle button is-primary" disabled>
                                    2. Adjust and set view</h2>
                                </div>
                                <div class="column"><h2 class="subtitle button is-primary" disabled>
                                    3. Add a caption</h2>
                                </div>
                                <div class="column"><h2 class="subtitle button is-primary" disabled>
                                    4. Share!</h2>
                                </div>
                            </div>
                            <div>
                                <button class="button title is-primary is-large" @click="triggerSelectPhoto"
                                        v-bind:class="{ 'is-loading' : photoLoading }">Click me to start sharing!</button>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </section>
    </div>
</template>
<script>

    export default {
        name: "Share",
        data() {
            return {
                ready: false,
                error: null,
                step: 0,
                zoom: 0,
                photoShareSuccess: false,
                photoLoading: false,
                photoLoaded: false,
                photo: {
                    latitude: null,
                    longitude: null,
                    caption: null
                },
                photoObject: null,
                cropItSettings: {
                    allowDragNDrop: false,
                    exportZoom: 1,
                    imageBackground: true,
                    imageBackgroundBorderWidth: 1,
                    onImageLoading: function() {
                        this.photoLoading = true;
                    },
                    onImageLoaded: function() {
                        this.photoLoaded = true;
                    },
                    onImageError: function() {
                        this.error = "Could not load photo. Please try again, or try another photo";
                    }
                },
                cropItExportOptions: {
                    type: "image/jpeg",
                    quality: 1,
                    originalSize: true
                }
            }
        },
        mounted() {
            this.ready = true;
            let me = this;

            this.$nextTick(function() {
                this.photoObject = jQuery("#photo-frame");
                this.photoObject.cropit(this.cropItSettings);
            });

            window.onbeforeunload = function() {
                if(me.photoLoaded &&
                    !confirm("You have a pending photo upload, do you want to finish that before leaving this page?")) {
                    return false;
                }
            };

        },
        methods: {
            goToNextStep() {
                this.step++;
            },
            goToPreviousStep() {
                if(this.step > 1) {
                    this.step--;
                }
            },
            rotate() {
                this.photoObject.cropit("rotateCW");
            },
            reset() {
                this.error = null;
                this.step = 0;
                this.photoLoading = false;
                this.photoLoaded = false;
                this.photo.latitude = null;
                this.photo.longitude = null;
                this.photo.caption = null;
            },
            cancel() {
                this.reset();
            },
            photoChanged(event) {
                let file = event.target.files[0],
                    me = this;

                if(!file) {
                    return;
                }

                this.photoLoading = true;

                me.$nextTick(function() {
                    EXIF.getData(file, function() {
                        if(typeof EXIF.getTag(this, "GPSLatitude") === typeof undefined) {
                            me.error = "Photo does not contain location data.<br>" +
                                "We can not accept photos without location data as they are impossible to place on " +
                                "the map, which indeed is the whole concept of this service. <br><br>" +
                                "Please try a new photo";
                            return false;
                        }

                        let exifData = EXIF.getAllTags(this),
                            lat = exifData.GPSLatitude,
                            lng = exifData.GPSLongitude;

                        // Convert coordinates to WGS84 decimal
                        let latRef = exifData.GPSLatitudeRef || "N",
                            lngRef = exifData.GPSLongitudeRef || "W";

                        me.photo.latitude = (lat[0] + lat[1]/60 + lat[2]/3600) * (latRef == "N" ? 1 : -1);
                        me.photo.longitude = (lng[0] + lng[1]/60 + lng[2]/3600) * (lngRef == "W" ? -1 : 1);

                        if(typeof exifData.DateTime !== typeof undefined) {
                            me.photo.taken_date = exifData.DateTime;
                        }
                        me.photoLoaded = true;
                    });
                });
            },
            storePhoto() {
                let me = this,
                    photoData = me.photo;

                me.goToNextStep();

                photoData.photo = me.photoObject.cropit("export", me.cropItExportOptions);

                Vue.axios.post("/tentsites", photoData).then(function(response) {
                    me.photoStored();
                }).catch(function(error) {
                    if(parseInt(error.response.readyState) === 0 && error.response.statusText === "abort") {
                        return;
                    }
                    let errorText = "";

                    if(typeof error.response.data.form_validations !== typeof undefined) {
                        error.response.data.form_validations.forEach(function(field, fieldError) {
                            errorText += fieldError + ". ";
                        });
                    }
                    me.error = errorText;
                });
            },
            abortStoring() {
                this.goToPreviousStep();
            },
            photoStored() {
                this.reset();
                this.photoShareSuccess = true;
            },
            triggerSelectPhoto() {
                this.photoShareSuccess = false;
                this.$refs.photo.click();
            }
        },
        watch: {
            zoom(newZoom) {
                if(newZoom > 1) {
                    this.zoom = 1;
                }
                else if(newZoom < 0) {
                    this.zoom = 0;
                }
            },
            step(newStep) {
                if(this.photoLoaded) {
                    if(newStep > 1) {
                        this.photoObject.cropit("disable");
                    } else {
                        this.photoObject.cropit("reenable");
                    }
                }
            },
            photoLoaded() {
                if(this.photoLoaded === true) {
                    this.photoLoading = false;
                    this.goToNextStep();
                }
            },
            error() {
                this.$store.dispatch("displayError", this.error);
                this.photoLoading = false;
            }
        }
    }
</script>