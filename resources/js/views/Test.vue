<template>
    <div class="container">
        Test component
    </div>
</template>

<script>

export default {
    name: "Test",
    components: {

    },
    created() {
        console.log(this.$route);
    },
    computed: {
    }
}
</script>

<style scoped>

</style>
