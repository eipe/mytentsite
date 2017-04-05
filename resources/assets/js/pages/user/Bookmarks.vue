<template>
    <div>
        <section class="hero is-info is-fullheight">
            <div class="hero-head">
                <div class="has-text-centered">
                    <br><br>
                    <h1 class="title">Feature under development</h1>
                    <transition enter-active-class="animated fadeIn">
                        <h2 class="subtitle" v-if="subtitle">{{ subtitle }}</h2>
                    </transition>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        name: 'User-bookmarks',
        data() {
            return {
                subtitle: ''
            }
        },
        created() {
            let me = this;
            setTimeout(function() {
                me.subtitle = 'Stay tuned - it won\'t take long time!';
            }, 1000);
        }
    }
</script>
