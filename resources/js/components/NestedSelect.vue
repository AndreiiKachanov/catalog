<template>
<!--    <pre>-->
<!--        {{ categories }}-->
<!--    </pre>-->
    <treeselect v-model="value" :multiple="false" :options="options" />
</template>

<script>
// import the component
    import Treeselect from 'vue3-treeselect'
    // import the styles
    import 'vue3-treeselect/dist/vue3-treeselect.css'

    export default {
        props: {
            categories: Object,
            name
        },
        components: {
            Treeselect
        },
        data: () => ({
            value: null,
            // define options
            options: [ {
                id: 'a',
                label: 'a',
                children: [ {
                    id: 'aa',
                    label: 'aa',
                }, {
                    id: 'ab',
                    label: 'ab',
                } ],
            }, {
                id: 'b',
                label: 'b',
            }, {
                id: 'c',
                label: 'c',
            } ],
        }),
        computed: {
        },
        methods: {
        }
    }
</script>

<style lang="scss">
</style>
