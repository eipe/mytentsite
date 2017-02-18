module.exports = function Sites() {
    var tentSites = [],
        nextPageUrl,
        loadedAll = false,
        config = {
            apiUrl : "/api/tentsites"
        };

    function getTime() {
        if(typeof Date.now !== typeof undefined) {
            return Date.now();
        } else {
            // Fallback for IE8
            return new Date().getTime();
        }
    }

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

        var loadedMoreSites = false;
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
                        loadedMoreSites = true;
                    }
                }
            }, error: function(error) {
                console.log(error);
            }
        });
        return loadedMoreSites;
    }


    return {
        "loadMore": function() {
            // Prevent fetching sites if all is already loaded
            if(loadedAll) {
                return false;
            }
            return fetchSites();
        },
        "getUserTentSites": function() {

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
        }
    }
}