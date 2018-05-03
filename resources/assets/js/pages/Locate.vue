<template>
    <section id="tentmap" class="hero is-fullheight is-fullheight-excluding-navbar"></section>
</template>
<style>
    #tentmap img, #tentmap video {
    max-width: 300px;
    width: 300px;
}
</style>
<script>
    var TentMap, locationCircle;

    // Configure layers
    var WorldImagery = L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
        attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, "+
        "GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"
    }), OpenStreetMap = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: ["a","b","c"]
    }), Kartverket_Topographic = L.tileLayer(
        "https://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=topo2&zoom={z}&x={x}&y={y}", {
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

    function placeSites(tentSites) {
        var photoLayer = L.photo.cluster().on("click", function (evt) {
            var photo = evt.layer.photo,
                template = '<img src="{thumbnail}" /></a><p>{caption}</p><p>{reported_by_name} - {created_at}</p>';
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

    export default {
        name: "Map",
        i18n: {
            messages: {
                en: {
                    viewPosition: 'View my position',
                    searchingForPosition: 'Searching for your position',
                    couldNotDetectLocation: 'Could not detect your location: {0}',
                },
                no: {
                    viewPosition: 'Vis min posisjon',
                    searchingForPosition: 'Søker etter din posisjon',
                    couldNotDetectLocation: 'Kunne ikke finne din posisjon: {0}',
                }
            }
        },
        data() {
            return {
                latitude: 61.651221,
                longitude: 8.557483,
                zoom: 4,
                tentSiteIds: [],
            }
        },
        computed: {
            tentSites() {
                return this.$store.state.tentSites;
            },
            position() {
                return new L.LatLng(this.latitude, this.longitude);
            }
        },
        activated() {
            if(!TentMap) {
                this.initializeMap();
            }
            if(typeof this.$route.query.latitude !== typeof undefined &&
                typeof this.$route.query.longitude !== typeof undefined) {
                this.latitude = this.$route.query.latitude;
                this.longitude = this.$route.query.longitude;
                this.zoom = 11;
            }

            let me = this,
                tentSiteIds = Object.keys(this.tentSites);

            if(tentSiteIds.length > 0) {
                tentSiteIds.filter(tentSiteId => {
                    if(me.tentSiteIds.indexOf(tentSiteId) === -1) {
                        return tentSiteId;
                    }
                });

                let newTentSites = [];
                tentSiteIds.forEach(tentSiteId => {
                    newTentSites.push(me.tentSites[tentSiteId]);
                    me.tentSiteIds.push(tentSiteId);
                });
                placeSites(newTentSites);
            }
        },
        methods: {
            initializeMap() {
                let me = this;
                TentMap = L.map("tentmap").setView(this.position, this.zoom);
                OpenStreetMap.addTo(TentMap);
                L.control.layers(baseMaps, overlayMaps).addTo(TentMap);

                // Add view position button
                let locationButton = L.easyButton({
                    position: "topleft",
                    states: [{
                        stateName: "locate",
                        icon: "fa-location-arrow",
                        title: me.$t('viewPosition'),
                        onClick: function(button, map) {
                            map.locate();
                            this.disable();
                            button.state("locating");
                        }
                    }, {
                        stateName: "locating",
                        icon: "fa-refresh fa-spin",
                        title: me.$t('searchingForPosition'),
                        onClick: function(button, map) {
                            map.locate();
                            this.enable();
                            button.state("locate");
                        }
                    }]
                }).addTo(TentMap);

                TentMap.on("locationfound", function(event) {
                    markLocation(event.latlng.lat, event.latlng.lng, event.accuracy);
                    TentMap.setView(event.latlng, 10);
                    locationButton.enable();
                    locationButton.state("locate");
                });

                TentMap.on("locationerror", function(event) {
                    me.$store.dispatch("displayError", me.$t('couldNotDetectLocation', [event.message]));
                    locationButton.enable();
                    locationButton.state("locate");
                });
            },
            updateView: function() {
                TentMap.setView(
                    this.position,
                    this.zoom,
                    {animate: true, duration: 0.2, noMoveStart: true, easyLinearity: 0.25}
                );
            }
        },
        watch: {
            position() {
                this.updateView();
            }
        }
    }
</script>
