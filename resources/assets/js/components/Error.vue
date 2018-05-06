<template>
    <div>
        <transition enter-active-class="animated bounceInDown">
            <div v-if="text" class="modal" v-bind:class="{ 'is-active' : text }">
                <div class="modal-content" @click="close">
                    <article v-if="text" class="message is-danger" @click="close">
                        <div class="message-header">
                            <p>{{ $t('error.title')}}</p>
                            <button class="delete" @click="close"></button>
                        </div>
                        <div class="message-body" v-html="text"></div>
                    </article>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
    export default{
        data() {
            return {
            }
        },
        computed: {
            text() {
                return this.$store.state.error;
            }
        },
        methods: {
            close() {
                this.$store.state.error = null;
            }
        }
    }
</script>
