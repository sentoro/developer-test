<template>
    <div v-if="isError" class="alert notification" :class="`alert-${type}`" role="alert">
        {{ message }}
    </div>
</template>

<script>
export default {
    name: 'Notification',

    props: {
        isError: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: '',
        },
        message: {
            type: String,
            default: '',
        }
    },

    data() {
        return {}
    },
}
</script>

<style scoped>
.notification {
    position: absolute;
    top: 20px;
    right: 15px;
}
</style>
