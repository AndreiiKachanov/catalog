<template>
    <div>
        <h1>{{ title }}</h1>
        <hr>
        Start from
        <router-link :to="{ name: 'products' }">main page</router-link>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: "404. Page not found"
        }
    }
}
</script>

<style scoped>
</style>
