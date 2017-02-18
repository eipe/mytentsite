<template>
    <div id="tentmap" class="page"></div>
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
        "http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
        attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, "+
        "GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"
    }), OpenStreetMap = L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: ["a","b","c"]
    }), Kartverket_Topographic = L.tileLayer(
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

    export default {
        name: 'Map',
        data() {
            return {
            }
        },
        computed: {
            tentSites() {
                return this.$store.state.tentSites.data;
            }
        },
        props: {
            latitude: {
                type: Number,
                default: 63.412222
            },
            longitude: {
                type: Number,
                default: 10.404722
            },
            zoom: {
                type: Number,
                default: 4
            }
        },
        created: function() {
            this.$nextTick(function() {
                this.initializeMap();
            });
        },
        methods: {
            initializeMap() {
                TentMap = L.map("tentmap").setView([this.latitude, this.longitude], this.zoom);
                OpenStreetMap.addTo(TentMap);
                L.control.layers(baseMaps, overlayMaps).addTo(TentMap);

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
                    //view.displayModalMessage("Could not detect your location", event.message);
                    alert("Could not detect your location:" + event.message);
                });

                placeSites(this.tentSites);
            },
            updateView: function() {
                if(!this.latitude || !this.longitude) {
                    return false;
                }
                TentMap.setView(
                    new L.LatLng(this.latitude, this.longitude),
                    zoom,
                    {animate: true, duration: 0.2, noMoveStart: true, easyLinearity: 0.25}
                );
            }
        },
        watch: {
            tentSites(newSites) {
                placeSites(newSites);
            },
            'latitude': function() {
                this.updateView();
            },
            'longitude': function() {
                this.updateView();
            }
        }
    }
</script>
