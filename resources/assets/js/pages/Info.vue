<template>
    <div class="page page-allow-overflow">
        <div class="row"><br /></div>
        <div class="row">
            <div class="columns large-centered text-center">
                <h4>mytentsite
                    <small v-if="staging"> - BETA</small>
                </h4>
                <img src="/images/500.png" />
                <div v-if="staging">
                    <br><br>
                    <i class="fa fa-exclamation-circle fa-4x"></i><br><br>
                    <h5>The tent site is not finished yet - stay tuned!</h5>
                    <p>We are still working on some features before we can publish this site, <br>
                        and appreciate that you have joined us one the journey.</p>
                    <p>Please submit both bug reports and feature ideas to us and we <br>
                        will take it into consideration on further development.<br>
                    </p>
                    <p><strong>You are first in line to be noticed when we are ready to launch this site :)</strong></p>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row background-light-gray">
            <div class="large-8 columns large-centered">
                <br><br>
                <div class="row medium-unstack text-left">
                    <div class="large-4 columns">
                        <h4><i class="fa fa-2x fa-picture-o"></i> Photo</h4>
                        <p>Select a photo of tent site you want to share with others and preferably a photo that
                            brings out what you think is great with just the place.</p>
                        <p><em>A photo is often worth more than a thousand words.</em></p>
                    </div>
                    <div class="large-4 columns">
                        <h4><i class="fa fa-2x fa-map-marker"></i> Location</h4>
                        <p>Location, location, location. It is not important whether the tent site is located on top of a
                            mountain or beside a lake in the middle of the forrest.
                            But to make it possible to locate the tent site and place it on the map, we need
                            the GPS information.
                        </p>
                    </div>
                    <div class="large-4 columns">
                        <h4><i class="fa fa-2x fa-font"></i> Caption</h4>
                        <p>Tell other campers why this is a preferable tent site, and give some extra information you think
                            can help them make a decision of where to go camping. Be short and concise, we can see your photo.
                        </p>
                    </div>
                </div>
                <br><br>
            </div>
        </div>
        <div class="row">
            <div class="large-8 columns large-centered">
                <br><br>
                <div class="medium-2 columns">
                    <i class="fa fa-4x fa-comment"></i>
                </div>
                <div class="medium-10 columns text-left">
                    <h4>The story and vision of mytentsite</h4>
                    <p>As we have focused on developing the story has not yet been written in words.</p>
                    <p>If you have the time and desire to help us, we would appreciate that a lot, and we think
                        this project deserves a good description. So please, do not hesitate to contact us if you
                        want to make your contribution to this project.</p>
                </div>
                <br><br>
            </div>
        </div>
        <br><br>
        <footer-component/>
    </div>
</template>
<script>

    import Footer from '../components/Footer.vue';

    export default{
        name: 'Info',
        props: ['staging'],
        components: {
            'footer-component': Footer
        }
    }
</script>
