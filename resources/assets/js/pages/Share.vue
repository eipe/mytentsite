<template>
    <div class="container">
        <section class="section" id="photo-frame">
            <transition enter-active-class="animated fadeIn">
                <ul class="steps is-medium is-horizontal has-content-centered" v-if="ready">
                    <li class="steps-segment is-clickable" v-if="photoLoaded" @click="cancel()">
                        <span class="steps-marker is-danger">
                            <i class="fa fa-trash-o"></i>
                        </span>
                        <div class="steps-content is-size-7">Cancel</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(0), 'is-clickable' : isCompletedStep(1) && !isStoringPhoto, 'has-gaps' : isUnCompletedStep(0)}"
                        @click="goToStepIfPossible(0)">
                        <span class="steps-marker">
                            <i class="fa fa-camera"></i>
                        </span>
                        <div class="steps-content is-size-7">Select photo</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(1), 'is-clickable' : isCompletedStep(1) && !isStoringPhoto, 'has-gaps' : isUnCompletedStep(1)}"
                        @click="goToStepIfPossible(1)">
                        <span class="steps-marker">
                            <i class="fa fa-crop"></i>
                        </span>
                        <div class="steps-content is-size-7">Adjust and set view</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(2), 'is-clickable' : isCompletedStep(2) && !isStoringPhoto, 'has-gaps' : isUnCompletedStep(2)}"
                        @click="goToStepIfPossible(2)">
                        <span class="steps-marker">
                            <i class="fa fa-pencil"></i>
                        </span>
                        <div class="steps-content is-size-7">Add a caption</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(3), 'is-success' : photoShareSuccess, 'has-gaps' : isUnCompletedStep(3)}">
                        <span class="steps-marker">
                            <i class="fa fa-check" v-bind:class="{'fa-circle-o-notch fa-spin' : isCurrentStep(3) && !photoShareSuccess}"></i>
                        </span>
                        <div class="steps-content is-size-7">Share!</div>
                    </li>
                </ul>
            </transition>
            <form @submit.prevent="storePhoto">
                <div v-show="isCurrentStep(0) || isCurrentStep(1)">
                    <input type="file" ref="photo" @change="photoChanged"
                           class="cropit-image-input is-hidden" />
                    <figure key="Preview" class="cropit-preview"
                        v-show="photoLoaded" title="Drag to adjust">
                    </figure>
                </div>
                <transition-group enter-active-class="animated fadeIn">
                    <div v-show="isCurrentStep(0)" key="Select photo" class="has-text-centered">
                        <span class="button title is-info is-medium" @click="triggerSelectPhoto"
                            v-bind:class="{ 'is-loading' : photoLoading }">
                            <slot v-if="photoLoaded">Click me to select another photo</slot>
                            <slot v-else>Click me to start sharing!</slot>
                        </span>
                    </div>
                    <div v-show="isCurrentStep(1)" key="Adjust photo">
                        <div class="buttons has-addons is-mobile">
                            <div class="control">
                                <div class="button is-light" @click="rotate" title="Rotate clockwise">
                                    <span class="icon">
                                        <i class="fa fa-rotate-right"></i>
                                    </span>
                                    <span>Rotate</span>
                                </div>
                            </div>
                            <div class="control">
                                <div class="button is-light" title="Click to zoom out" @click="zoomOut" v-bind="{ 'disabled' : isZoomedOut() }">
                                    <span class="icon">
                                        <i class="fa fa-search-minus"></i>
                                    </span>
                                    <span>Zoom out</span>
                                </div>
                            </div>
                            <div class="control">
                                <div class="button is-light" title="Click to zoom in" @click="zoomIn" v-bind="{ 'disabled' : isZoomedIn() }">
                                    <span class="icon">
                                        <i class="fa fa-search-plus"></i>
                                    </span>
                                    <span>Zoom in</span>
                                </div>
                            </div>
                            <div class="control">
                                <div class="button is-light" title="Proceed" @click="goToNextStep">
                                    <span>Next</span>
                                    <span class="icon">
                                        <i class="fa fa-step-forward"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div key="Add caption" v-show="isCurrentStep(2)">
                        <div class="field">
                            <input name="caption" title="Caption"
                                      placeholder="Caption"
                                      v-model="photo.caption"
                                      maxlength="255"
                                      class="input" required>
                        </div>
                        <div class="field">
                            <input type="submit" class="button is-success" value="Share"
                                   title="Share this tent site">
                        </div>
                    </div>
                    <div key="Share tent site" v-if="isCurrentStep(3)" class="has-text-centered">
                        We are uploading your tent site
                        <i class="fa fa-circle-o-notch fa-spin"></i><br><br>
                        <span class="button is-danger" @click.prevent="abortStoring">
                            Cancel upload</span>
                    </div>
                </transition-group>
            </form>
            <transition enter-active-class="animated fadeIn">
                <div class="has-text-centered is-clickable" v-if="photoShareSuccess">
                    <h1 class="title" @click="photoShareSuccess = false">Thank's for your contribution!</h1>
                    <h2 class="subtitle">Click me if you want to start over again</h2>
                </div>
            </transition>
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
                isStoringPhoto: false,
                photoShareSuccess: false,
                photoLoading: false,
                photoLoaded: false,
                photo: {
                    latitude: null,
                    longitude: null,
                    caption: null
                },
                completedSteps: [],
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
            isZoomedIn() {
                return (this.zoom === 1);
            },
            isZoomedOut() {
                return (this.zoom === 0);
            },
            zoomIn() {
                if(this.isZoomedIn()) {
                    return;
                }
                this.zoom += 0.1
            },
            zoomOut() {
                if(this.isZoomedOut()) {
                    return;
                }
                this.zoom -= 0.1;
            },
            isCurrentStep(step) {
                return (step === this.step);
            },
            isUnCompletedStep(step) {
                return (step >= this.step);
            },
            isCompletedStep(step) {
                return (this.completedSteps.indexOf(step) > -1);
            },
            goToNextStep() {
                this.completedSteps.push(this.step);
                let nextStep = this.step +1;
                this.goToStep(nextStep);
            },
            goToPreviousStep() {
                if(this.step > 1) {
                    let prevStep = this.step-1;
                    this.goToStep(prevStep);
                }
            },
            goToStep(step) {
                this.step = step;
                this.completedSteps.push(step);
            },
            goToStepIfPossible(step) {
                if(this.isCompletedStep(step) && !this.isStoringPhoto) {
                    this.step = step;
                }
            },
            rotate() {
                this.photoObject.cropit("rotateCW");
            },
            reset() {
                this.error = null;
                this.step = 0;
                this.isStoringPhoto = false;
                this.completedSteps = [];
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

                me.isStoringPhoto = true;
                Vue.axios.post("/tentsites", photoData).then(function(response) {
                    me.photoStored();
                }).catch(function(error) {
                    me.isStoringPhoto = false;
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

                let me = this;
                this.$nextTick(() => {
                    me.photoObject.cropit("zoom", me.zoom);
                });
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