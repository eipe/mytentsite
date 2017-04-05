<template>
    <div class="has-fixed-header">
        <section class="hero is-fullheight is-info">
            <div class="hero-body">
                <transition enter-active-class="animated fadeIn">
                    <div class="container" v-show="isLoaded">
                        <div class="columns">
                            <div class="column has-text-centered">
                                <router-link to="share">
                                    <h1 class="title">Share</h1>
                                    <h2 class="subtitle is-6">Your favourites with others</h2>
                                    <i class="fa fa-camera icon is-large"/>
                                </router-link>
                            </div>
                            <div class="column has-text-centered">
                                <router-link to="map">
                                    <h1 class="title">Locate</h1>
                                    <h2 class="subtitle is-6">Tentsites all over the world</h2>
                                    <i class="fa fa-map icon is-large"/>
                                </router-link>
                            </div>
                            <div class="column has-text-centered">
                                <router-link to="wall">
                                    <h1 class="title">Explore</h1>
                                    <h2 class="subtitle is-6">Tentsites on the photo wall</h2>
                                    <i class="fa fa-th icon is-large"/>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </section>
        <section class="hero is-medium">
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
        <section class="hero is-warning">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">The story and vision of mytentsite</h1>
                    <h2 class="subtitle">Where it all started, and where we are now</h2>
                    <p>As we have focused on developing the story has not yet been written in words.</p>
                    <p>If you have the time and desire to help us, we would appreciate that a lot, and we think
                        this project deserves a good description. So please, do not hesitate to contact us if you
                        want to make your contribution to this project.</p>
                </div>
            </div>
        </section>
        <footer-component/>
    </div>
</template>
<script>

    import Footer from '../components/Footer.vue';

    export default {
        name: 'Info',
        data() {
            return {
                isLoaded: false
            }
        },
        created() {
            let me = this;
            setTimeout(function() {
                me.isLoaded = true;
            }, 500)
        },
        components: {
            'footer-component': Footer
        }
    }
</script>
