<template>
    <div class="has-fixed-header">
        <section class="hero is-small is-info">
            <div class="hero-body">
                <div class="container">
                    <transition enter-active-class="animated fadeIn">
                        <div class="columns is-mobile" v-show="isLoaded" v-cloak>
                            <div class="column has-text-centered">
                                <p class="title is-marginless is-paddingless">{{ statistics.numberOfTentSites }}</p>
                                <p class="heading">{{ $t('info.tentSites')}}</p>
                            </div>
                            <div class="column has-text-centered">
                                    <p class="title is-marginless is-paddingless">{{ statistics.numberOfBookmarkedTentSites }}</p>
                                    <p class="heading">{{ $t('info.bookmarks')}}</p>
                            </div>
                            <div class="column has-text-centered">
                                <p class="title is-marginless is-paddingless">{{ statistics.numberOfCountries }}</p>
                                <p class="heading">{{ $t('info.countries')}}</p>
                            </div>
                            <div class="column has-text-centered">
                                <div>
                                    <p class="title is-marginless is-paddingless">{{ statistics.numberOfContributors }}</p>
                                    <p class="heading">{{ $t('info.contributors')}}</p>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </section>
        <section class="hero">
            <div class="hero-body">
                <transition enter-active-class="animated fadeIn">
                    <div class="container" v-if="isLoaded" v-cloak>
                        <h1 class="title">The vision of mytentsite and the story behind</h1>
                        <h2 class="subtitle">What is it? And why did we create it?</h2>
                        <p>The main purpose of this service is to <strong>make it easy to find a tent site</strong> without making a lot
                            of research.<br>
                            When traveling around it can be hard to find a tent site if you are not planning to stay at established camp sites.<br>
                            We host this service to inspire others using and taking care of our great nature.
                        </p><br>
                        <h3 class="subtitle">Take care of the nature and local environment</h3>
                        <p>The nature is free to use, but it is our responsibility to keep it intact so others can enjoy the same as us.</p>
                        <p>We also want to encourage you to follow local restrictions and make sure that you <strong>bring all trash and non biodegradable material for recycling</strong>!</p>
                    </div>
                </transition>
            </div>
        </section>
        <section class="hero is-small is-light">
            <div class="hero-body">
                <div class="container">
                    <transition enter-active-class="animated fadeIn">
                        <nav class="level" v-if="isLoaded" v-cloak>
                            <div class="level-item has-text-centered">
                                <router-link to="share">
                                <div>
                                    <p class="title">{{ $t('action.share')}}<i class="fa fa-camera icon is-large"></i></p>
                                    <p class="heading">Your favourites with others</p>
                                </div>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="locate">
                                <div>
                                    <p class="title">{{ $t('locate')}}<i class="fa fa-map icon is-large"></i></p>
                                    <p class="heading">Tent sites all over the world</p>
                                </div>
                                </router-link>
                            </div>
                            <div class="level-item has-text-centered">
                                <router-link to="explore">
                                <div>
                                    <p class="title">{{ $t('explore')}}<i class="fa fa-th icon is-large"></i></p>
                                    <p class="heading">Tent sites on the photo wall</p>
                                </div>
                                </router-link>
                            </div>
                        </nav>
                    </transition>
                </div>
            </div>
        </section>
        <transition enter-active-class="animated fadeIn">
            <section class="hero is-medium" id="info-share" v-if="isLoaded">
                <div class="hero-body">
                    <div class="container">
                        <h1 class="title">How do I share?</h1>
                        <h2 class="subtitle">Select a photo with location, add a caption and share it!</h2>
                        <hr/>
                        <div class="columns">
                            <div class="column">
                                <p class="subtitle is-4">1. Select a photo <small>- with location</small></p>
                                <p>
                                    Take a new photo or upload one from your collection. Preferably a photo that
                                    brings out what you think is great with just the place.</p>
                                <br>
                                <p>It is not important whether the tent site is located on top of a
                                    mountain or beside a lake in the middle of the forrest.
                                    But to make it possible to locate the tent site and place it on the map, we need
                                    the GPS information.asd
                                </p>
                            </div>
                            <div class="column">
                                <p class="subtitle is-4">2. Add caption</p>
                                <p>Tell other campers why this is a preferable tent site,
                                    and give some extra information you think can help them make a decision of where
                                    to go camping. Be short and concise, we can see your photo.
                                </p>
                            </div>
                            <div class="column">
                                <p class="subtitle is-4">3. Share it!</p>
                                <p>Once you have added the details you think is relevant for the photo, just hit the
                                    share button! Your contribution will then be reviewed before it is published to the wall.
                                    You can at any time access your own contributions through your personal page.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </transition>
        <footer-component/>
    </div>
</template>
<script>

    import Footer from "../components/Footer.vue"

    export default {
        name: "Info",
        i18n: {
            messages: {
                en: {
                    info: {
                        tentSites: 'Shared tent sites',
                        bookmarks: 'Bookmarked tent sites',
                        countries: 'Countries represented',
                        contributors: 'Contributors',
                    }
                },
                no: {
                    info: {
                        sharedSites: 'Delte teltplasser',
                        bookmarks: 'Bokmerkede teltplasser',
                        countries: 'Land representer',
                        contributors: 'Bidragsytere'
                    }
                }
            }
        },
        data() {
            return {
                statistics: {
                    numberOfTentSites: 0,
                    numberOfBookmarkedTentSites: 0,
                    numberOfCountries: 0,
                    numberOfContributors: 0,
                },
                isLoaded: false
            }
        },
        created() {
            let me = this;

            Vue.axios.get('statistics').then((response) => {
                me.statistics.numberOfTentSites = response.data.tentSites;
                me.statistics.numberOfBookmarkedTentSites = response.data.bookmarkedTentSites;
                me.statistics.numberOfCountries = response.data.countries;
                me.statistics.numberOfContributors = response.data.contributors;
            });

            setTimeout(function() {
                me.isLoaded = true;
            }, 500)
        },
        components: {
            "footer-component": Footer
        }
    }
</script>
