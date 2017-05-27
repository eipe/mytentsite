<template>
    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column is-half">
                    <h1 class="title is-5">Spread the word and inspire others</h1>
                    <h2 class="subtitle is-6">- to share their favourite tent sites!</h2>
                    <p class="content is-small">We encourage you to bring this message further on, in hope of a growing
                        database of tent sites throughout the world. We will promote tent sites through our
                        instagram account on request, to spread the word and inspire to share.</p>
                    <br>
                    <p>
                        <a href="https://instagram.com/mytentsite" target="_blank" title="Instagram - @mytentsite"
                           class="button is-light">
                            <span class="icon is-medium"><i class="fa fa-instagram"></i></span>
                            <span>Instagram</span>
                        </a>
                        <a href="https://facebook.com/mytentsite" target="_blank" title="Facebook - mytentsite"
                           class="button is-light">
                            <span class="icon is-medium"><i class="fa fa-facebook"></i></span>
                            <span>Facebook</span>
                        </a>
                    </p>
                    <br>
                    <p class="content is-small"><small>
                        We use cookies to give you the best possible user experience.
                        With continued use of our service, we assume that you agree to our use of cookies.</small>
                    </p>
                </div>
                <div class="column is-half">
                    <h1 class="title is-5">About</h1>
                    <h2 class="subtitle is-6">- people behind and site info</h2>
                    <p class="content is-small">
                        The concept and solution <strong>mytentsite</strong> is founded, developed and maintained
                        by Petter Alstad and Eivind Røe (Eipe).<br>
                        We launched the first version in 2017 after months of planning and development and proudly
                        present to you this collaborative tent collective!
                    </p>
                    <p class="content is-small">
                        Feel free to contact us through or social profiles or directly on email
                        mytentsite@gmail.com if you have any questions, ideas or bug reports.
                        We really would appreciate your inputs!
                    </p>
                </div>
                <div class="column" v-if="beta">
                    <h1 class="title">BETA</h1>
                    <h2 class="subtitle">- work in progress</h2>
                    <p>We are still working on some features before we can publish this site,
                        and appreciate that you have joined us one the journey.<br><br>
                        Please submit both bug reports and feature ideas to us through our
                        <a href="https://facebook.com/mytentsite" target="_blank"
                           title="Facebook - mytentsite">facebook page</a> and we
                        will take it into consideration on further development.</p>
                </div>
            </div>
        </div>
    </footer>
</template>
<script>
    export default {
        name: "Footer",
        computed: {
            beta() {
                return this.$store.state.beta;
            }
        }
    }
</script>