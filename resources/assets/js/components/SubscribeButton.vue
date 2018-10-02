<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: ['dataActive'],

        data() {
            return {
                active: this.dataActive
            };
        },
        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-secondary'];
            }
        },
        methods: {
            subscribe() {
                axios[(this.active ? 'delete' : 'post')](location.pathname + '/subscriptions');

                this.active = ! this.active;
            }
        }
    }
</script>
