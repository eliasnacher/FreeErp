<template>
    <a :href="url">
        <div :class="defaultClassWithProp">
            {{ title }}
        </div>
    </a>
</template>

<script>
    export default {
        name: 'BlButton',
        props: {
            url: String,
            title: String,
            class: String,
        },
        computed: {
            defaultClassWithProp: function () {
                return `bl-input bl-button ${this.class != undefined ? this.class : ''}`
            }
        }
    }
</script>