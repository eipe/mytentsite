<template>
    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <p class="subtitle is-4">
                        Spread the word and inspire others<br><small>- to share their favourite tent sites!</small></p>
                    <p><small>We encourage you to bring this message further on, in hope of a growing database of
                        tent sites throughout the world. We will promote tent sites through this instagram account on
                        request, to spread the word and inspire to share.</small></p>
                    <p><a href="https://instagram.com/mytentsite"
                          title="Instagram - @mytentsite" target="_blank"><i class="fa fa-2x fa-instagram"></i>
                    </a> <a href="https://facebook.com/mytentsite"
                            title="Facebook - mytentsite" target="_blank"><i class="fa fa-2x fa-facebook-square"></i>
                    </a></p>
                    <br>
                    <p><small>
                        We use cookies to give you the best possible user experience.<br>
                        With continued use of our service, we assume that you agree to our use of cookies.</small>
                    </p>
                </div>
                <div class="column" v-if="beta">
                    <p class="subtitle is-4">BETA <i class="fa fa-exclamation-circle fa-4x"></i></p>
                    <p>We are still working on some features before we can publish this site, <br>
                        and appreciate that you have joined us one the journey.</p>
                    <p>Please submit both bug reports and feature ideas to us and we <br>
                        will take it into consideration on further development.<br>
                    </p>
                    <p>
                        <strong>
                            You are first in line to be noticed when we are ready to launch this site :)
                        </strong>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>
<script>
    export default {
        name: 'Footer',
        computed: {
            beta() {
                return this.$store.state.beta;
            }
        }
    }
</script>