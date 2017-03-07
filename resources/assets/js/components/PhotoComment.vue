<template>
    <div class="content">
        <p>
            <strong>{{ comment_by }}</strong> <small>{{ created_at }}</small>
            <br>
            {{ comment }}
        </p>
    </div>
</template>
<script>
    export default {
        name: 'PhotoComment',
        data() {
            return {
            }
        },
        props: {
            'comment_by': {
                type: String,
                required: true
            },
            'created_at': {
                type: String,
                require: true
            },
            'comment': {
                type: String,
                required: true
            }
        }
    }
</script>
