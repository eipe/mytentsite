<template>
    <div>
        <section class="hero is-info is-primary">
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
        <section class="hero is-medium">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">How does it work?</h1>
                    <h2 class="subtitle">Select a photo with location, add a caption and share it!</h2>
                    <br><br>
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
        <footer-component/>
    </div>
</template>
<script>

    import Footer from '../components/Footer.vue';

    export default {
        name: 'Info',
        components: {
            'footer-component': Footer
        }
    }
</script>
