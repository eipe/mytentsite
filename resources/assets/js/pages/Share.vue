<template>
    <div class="container">
        <section class="section" id="photo-frame">
            <transition enter-active-class="animated fadeIn">
                <ul class="steps is-medium is-horizontal has-content-centered" v-if="ready">
                    <li class="steps-segment is-clickable" v-if="photoLoaded" @click="cancel()">
                        <span class="steps-marker is-danger">
                            <i class="fa fa-trash-o"></i>
                        </span>
                        <div class="steps-content is-size-7">{{ $t('action.cancel')}}</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(0), 'is-clickable' : isCompletedStep(1) && !isSharing, 'has-gaps' : isUnCompletedStep(0)}"
                        @click="goToStepIfPossible(0)">
                        <span class="steps-marker">
                            <i class="fa fa-camera"></i>
                        </span>
                        <div class="steps-content is-size-7">{{ $t('action.selectPhoto')}}</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(1), 'is-clickable' : isCompletedStep(1) && !isSharing, 'has-gaps' : isUnCompletedStep(1)}"
                        @click="goToStepIfPossible(1)">
                        <span class="steps-marker">
                            <i class="fa fa-crop"></i>
                        </span>
                        <div class="steps-content is-size-7">{{ $t('action.adjust')}}</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(2), 'is-clickable' : isCompletedStep(2) && !isSharing, 'has-gaps' : isUnCompletedStep(2)}"
                        @click="goToStepIfPossible(2)">
                        <span class="steps-marker">
                            <i class="fa fa-pencil"></i>
                        </span>
                        <div class="steps-content is-size-7">{{ $t('action.addInfo')}}</div>
                    </li>
                    <li class="steps-segment"
                        v-bind:class="{'is-active' : isCurrentStep(3) || isCurrentStep(4), 'is-success' : isShareSuccess, 'has-gaps' : isUnCompletedStep(3)}">
                        <span class="steps-marker">
                            <i class="fa fa-check"
                               v-bind:class="{'fa-circle-o-notch fa-spin' : isCurrentStep(4) && !isShareSuccess}"></i>
                        </span>
                        <div class="steps-content is-size-7">{{ $t('action.reviewAndShare')}}</div>
                    </li>
                </ul>
            </transition>
            <form @submit.prevent="storePhoto">
                <div v-show="isCurrentStep(0) || isCurrentStep(1)">
                    <input type="file" ref="photo" @change="photoChanged"
                           class="cropit-image-input is-hidden" />
                    <figure key="Preview" class="cropit-preview"
                        v-show="photoLoaded" :title="$t('action.dragToAdjust')">
                    </figure>
                </div>
                <transition-group enter-active-class="animated fadeIn">
                    <div v-show="isCurrentStep(0)" key="Select photo" class="has-text-centered">
                        <span class="button title is-info is-medium" @click="triggerSelectPhoto"
                            v-bind:class="{ 'is-loading' : photoLoading }">
                            <slot v-if="photoLoaded">{{ $t('action.clickToSelectAnotherPhoto')}}</slot>
                            <slot v-else>{{ $t('clickToStartShare')}}</slot>
                        </span>
                    </div>
                    <div v-show="isCurrentStep(1)" key="Adjust photo">
                        <div class="buttons is-centered has-addons is-mobile">
                            <div class="control">
                                <div class="button is-light"
                                     @click="rotate"
                                     :title="$t('action.rotateClockwise')">
                                    <span class="icon">
                                        <i class="fa fa-rotate-right"></i>
                                    </span>
                                    <span>{{ $t('action.rotate')}}</span>
                                </div>
                            </div>
                            <div class="control">
                                <div class="button is-light"
                                     :title="$t('action.clickToZoomOut')"
                                     @click="zoomOut" v-bind="{ 'disabled' : isZoomedOut() }">
                                    <span class="icon">
                                        <i class="fa fa-search-minus"></i>
                                    </span>
                                    <span>{{ $t('action.zoomOut')}}</span>
                                </div>
                            </div>
                            <div class="control">
                                <div class="button is-light"
                                     :title="$t('action.clickToZoomIn')"
                                     @click="zoomIn" v-bind="{ 'disabled' : isZoomedIn() }">
                                    <span class="icon">
                                        <i class="fa fa-search-plus"></i>
                                    </span>
                                    <span>{{ $t('action.zoomIn')}}</span>
                                </div>
                            </div>
                            <div class="control">
                                <div class="button is-light"
                                     :title="$t('action.next')"
                                     @click="goToNextStep">
                                    <span>{{ $t('action.next')}}</span>
                                    <span class="icon">
                                        <i class="fa fa-step-forward"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div key="Add information" v-show="isCurrentStep(2)">
                        <div class="tags">
                            <div class="tag is-clickable"
                                 v-for="tag in availableTags"
                                 v-bind:class="{'is-success' : isTagSelected(tag)}"
                                 @click="toggleTag(tag)">
                                {{ tag.name }}
                            </div>
                        </div>
                        <div class="field">
                            <textarea name="caption"
                                      :title="$t('tentSite.caption')"
                                      :placeholder="$t('tentSite.caption')"
                                      v-model="tentSite.caption"
                                      :maxlength="rules.captionMaxLength"
                                      class="textarea" required></textarea>
                        </div>
                        <div class="field is-pulled-right">
                            <div class="control">
                                <span class="button is-white is-disabled">
                                    {{ $t('captionLength', [(rules.captionMaxLength - tentSite.caption.length)])}}
                                </span>
                                <div class="button is-light is-right"
                                     :title="$t('action.next')"
                                     @click="goToNextStep">
                                    <span>{{ $t('action.next')}}</span>
                                    <span class="icon">
                                        <i class="fa fa-step-forward"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div key="Review tent site" v-if="isCurrentStep(3)">
                        <div class="columns">
                            <div class="column is-half is-offset-one-quarter">
                                <div class="card">
                                    <div class="card-image">
                                        <figure class="image is-4by3">
                                            <img :src="tentSite.photo" />
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <div class="tags">
                                            <div class="tag" v-for="tag in tentSite.tags">
                                                {{ getTagName(tag) }}
                                            </div>
                                        </div>
                                        <div class="media">
                                            <div class="media-left">
                                                <p class="title is-4">{{ userName }}</p>
                                                <p class="subtitle is-6">{{ currentDate }}</p>
                                            </div>
                                            <div class="media-content"></div>
                                            <div class="media-right">
                                                <div class="buttons has-addons">
                                                    <input type="submit"
                                                            class="button is-success"
                                                            :value="$t('action.share')"
                                                            :title="$t('action.share')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">{{ tentSite.caption }}</div>
                                        <div class="content is-small" v-if="tentSite.takenDate">
                                            {{ $t('photoWasTaken', [tentSite.takenDate])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div key="Share tent site" v-if="isCurrentStep(4)" class="has-text-centered">
                        {{ $t('progressUploading')}}
                        <i class="fa fa-circle-o-notch fa-spin"></i><br><br>
                        <span class="button is-danger" @click.prevent="abortStoring">
                            {{ $t('action.cancel')}}</span>
                    </div>
                </transition-group>
            </form>
            <transition enter-active-class="animated fadeIn">
                <div class="has-text-centered is-clickable" v-if="isCurrentStep(3) && isShareSuccess">
                    <h1 class="title">{{ $t('thanksForContributing')}}</h1>
                    <span class="button" @click="isShareSuccess = false">
                        {{ $t('clickToStartOverAgain')}}
                    </span>
                </div>
            </transition>
        </section>
    </div>
</template>
<script>

    export default {
        name: "Share",
        i18n: {
            messages: {
                en: {
                    thanksForContributing: 'Thank\'s for your contribution!',
                    clickToStartShare: 'Click me to start sharing',
                    clickToStartOverAgain: 'Click me if you want to start over again',
                    progressUploading: 'We are uploading your tent site',
                    action: {
                        cancel: 'Cancel upload',
                        share: 'All good. Share it!',
                        dragToAdjust: 'Drag to adjust',
                        selectPhoto: 'Select photo',
                        clickToSelectAnotherPhoto: 'Click me to select another photo',
                        adjust: 'Adjust and set view',
                        addInfo: 'Add a information',
                        reviewAndShare: 'Review and share!',
                        rotateClockwise: 'Rotate clockwise',
                        rotate: 'Rotate',
                        zoomIn: 'zoom in',
                        zoomOut: 'zoom out',
                        clickToZoomIn: 'click to zoom in',
                        clickToZoomOut: 'click to zoom out',
                    },
                    photoWasTaken: 'Photo was taken {0}',
                    captionLength: '{0} characters left',
                    warning: 'You have a pending photo upload, do you want to finish that before leaving this page?',
                    error: {
                        location: 'Photo does not contain location data.<br> We can not accept photos without location data as they are impossible to place on the map, which indeed is the whole concept of this service. <br><br> Please try a new photo',
                    }
                },
                no: {
                    thanksForContributing: 'Takk for ditt bidrag!',
                    clickToStartShare: 'Klikk meg for å starte deling',
                    clickToStartOverAgain: 'Klikk meg hvis du vil starte på nytt',
                    progressUploading: 'Vi laster opp teltplassen din',
                    action: {
                        cancel: 'Avbryt deling',
                        share: 'Alt klart. Del tentplassen!',
                        dragToAdjust: 'Dra for å justere',
                        selectPhoto: 'Velg foto',
                        clickToSelectAnotherPhoto: 'Klikk meg for å velge et annet foto',
                        adjust: 'Juster foto',
                        addInfo: 'Legg til informasjon',
                        reviewAndShare: 'Se over og del!',
                        rotateClockwise: 'Roter med klokken',
                        rotate: 'Roter',
                        zoomIn: 'zoom inn',
                        zoomOut: 'zoom ut',
                        clickToZoomIn: 'klikk for å zoom\'e inn',
                        clickToZoomOut: 'klikk for å zoom\'e ut',
                    },
                    photoWasTaken: 'Foto ble tatt {0}',
                    captionLength: '{0} tegn til gode',
                    warning: 'Du har ikke delt teltplassen enda, vil du fullføre det før du forlater denne siden?',
                    error: {
                        location: 'Foto inneholder ikke lokasjonsdata.<br>Vi kan ikke akseptere foto uten lokasjonsdata, da det gjør det umulig å plassere teltplassen på kartet, noe som er hele konseptet med denne tjenesten <br><br>Vennligst prøv et annet foto',
                    }
                }
            }
        },
        data() {
            return {
                ready: false,
                error: null,
                step: 0,
                zoom: 0,
                isSharing: false,
                isShareSuccess: false,
                photoLoading: false,
                photoLoaded: false,
                photoObject: null,
                rules: {
                    captionMaxLength: 255
                },
                availableTags: [],
                tentSite: {
                    latitude: null,
                    longitude: null,
                    caption: "",
                    takenDate: null,
                    photo: null,
                    tags: []
                },
                currentDate: "Now",
                completedSteps: [],
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
        computed: {
            userName() {
                return this.$auth.user().name;
            }
        },
        mounted() {
            this.ready = true;
            let me = this;

            this.$nextTick(function() {
                this.photoObject = jQuery("#photo-frame");
                this.photoObject.cropit(this.cropItSettings);
            });

            Vue.axios.get('tags').then(response => {
                response.data.data.forEach(tag => {
                    me.availableTags.push({ id: tag.id, name: tag.name});
                });
            });

            window.onbeforeunload = function() {
                if(me.photoLoaded &&
                    !confirm(me.$t('warning'))) {
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
            toggleTag(tag) {
                let index = this.tentSite.tags.indexOf(tag.id);
                if(index > -1) {
                    this.tentSite.tags.splice(index, 1);
                } else {
                    this.tentSite.tags.push(tag.id);
                }
            },
            getTagName(tagId) {
                let tag = this.availableTags.find((findTag) => {
                    return (findTag.id === tagId);
                });
                if(tag) {
                    return tag.name;
                }
                return '';
            },
            isTagSelected(tag) {
                return (this.tentSite.tags.indexOf(tag.id) > -1);
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
                if(this.isCompletedStep(step) && !this.isSharing) {
                    this.step = step;
                }
            },
            rotate() {
                this.photoObject.cropit("rotateCW");
            },
            reset() {
                this.error = null;
                this.step = 0;
                this.isSharing = false;
                this.completedSteps = [];
                this.photoLoading = false;
                this.photoLoaded = false;
                this.tentSite.latitude = null;
                this.tentSite.longitude = null;
                this.tentSite.caption = "";
                this.tentSite.photo = null;
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

                // Reset exported photo as user has changed photo
                this.tentSite.photo = null;

                this.photoLoading = true;

                me.$nextTick(function() {
                    EXIF.getData(file, function() {
                        if(typeof EXIF.getTag(this, "GPSLatitude") === typeof undefined) {
                            me.error = me.$t('error.location');
                            return false;
                        }

                        let exifData = EXIF.getAllTags(this),
                            lat = exifData.GPSLatitude,
                            lng = exifData.GPSLongitude;

                        // Convert coordinates to WGS84 decimal
                        let latRef = exifData.GPSLatitudeRef || "N",
                            lngRef = exifData.GPSLongitudeRef || "W";

                        me.tentSite.latitude = (lat[0] + lat[1]/60 + lat[2]/3600) * (latRef == "N" ? 1 : -1);
                        me.tentSite.longitude = (lng[0] + lng[1]/60 + lng[2]/3600) * (lngRef == "W" ? -1 : 1);

                        if(typeof exifData.DateTime !== typeof undefined) {
                            me.tentSite.takenDate = exifData.DateTime;
                        }
                        me.photoLoaded = true;
                    });
                });
            },
            exportPhoto() {
                return this.photoObject.cropit("export", this.cropItExportOptions);
            },
            storePhoto() {
                let me = this;

                me.goToNextStep();

                if(me.tentSite.photo) {
                    me.tentSite.photo = me.exportPhoto();
                }

                me.isStoringPhoto = true;
                Vue.axios.post("/tentsites", me.tentSite).then(function(response) {
                    me.photoStored();
                }).catch(function(error) {
                    me.isSharing = false;
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
                this.isShareSuccess = true;
            },
            triggerSelectPhoto() {
                this.isShareSuccess = false;
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

                if(newStep === 3 && !this.tentSite.photo) {
                    this.tentSite.photo = this.exportPhoto();
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