<template>
    <div class="page">
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
                <div id="photo-controllers" data-current-step="1" class="is-hidden small-centered color-gray">
                <span data-step="1">
                    <i title="Cancel" class="photo-cancel is-clickable fa fa-trash-o"></i>
                    <i id="photo-rotate" title="Rotate clockwise"
                       class="is-clickable fa fa-rotate-right"></i>
                    <i class="fa fa-image"></i>
                    <input type="range" title="Drag to zoom" min="0" max="1" step="0.1"
                           class="cropit-image-zoom-input" />
                    <i class="fa fa-image fa-2x"></i>
                    <i class="photo-controllers-next fa fa-arrow-right is-clickable" title="Proceed"></i>
                </span>
                    <span data-step="2" class="is-hidden">
                    <i title="Cancel" class="photo-cancel is-clickable fa fa-trash-o"></i>
                    <i class="photo-controllers-previous fa fa-arrow-left is-clickable"></i>
                    <textarea name="caption" id="photo-caption" title="Caption"
                              placeholder="Caption" maxlength="255"></textarea>
                    <i id="photo-store" title="Share this tent site"
                       data-icon-store="fa-check"
                       data-icon-storing="fa-spin fa-circle-o-notch"
                       class="is-clickable fa fa-check"></i>
                </span>
                    <span data-step="3" class="is-hidden">
                    We are uploading your tent site
                    <i class="fa fa-circle-o-notch fa-spin"></i><br><br>
                    <button id="photo-cancel-upload" class="button secondary">Cancel upload</button>
                </span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    var Share = require('../share.js');

    export default {
        name: 'Share',
        data() {
            return {
            }
        },
        created() {
            this.$nextTick(function() {
                Share().initialize();
            });
        },
        components: {
        }
    }
</script>
