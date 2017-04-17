<template>
    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <h1 class="title">Spread the word and inspire others</h1>
                    <h2 class="subtitle">- to share their favourite tent sites!</h2>
                    <p>We encourage you to bring this message further on, in hope of a growing database of
                        tent sites throughout the world. We will promote tent sites through our instagram account on
                        request, to spread the word and inspire to share.</p>
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
                    <p><small>
                        We use cookies to give you the best possible user experience.
                        With continued use of our service, we assume that you agree to our use of cookies.</small>
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