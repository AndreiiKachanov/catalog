<template>
    <div class="container-fluid top-menu">
        <div class="row justify-content-between">
            <div class="col-12 parent">
                    {{ parentTitle }}
            </div>
                <router-link
                    class="child col-5 my-1 pt-4 pb-4"
                    v-for="category in subCategories"
                    :key="category.route"
                    :to="`/category/${category.slug}`"> {{ category.title }}
                </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CategoriesMenu",
        props: ['subCategories', 'parentTitle']
    }
</script>

<style lang="scss">
    .top-menu {
        .parent {
            text-align: center;
            display: block;
            color: white;
            font-weight: 700;
            text-decoration: none;
            user-select: none;
            padding: 1em 2em;
            border: 1px solid;
            border-radius: 1px;
            word-break: break-all;
            background-color: #c1034a;
        }
        .child {
            text-align: center;
            display: block;
            color: #000000;
            font-weight: 700;
            text-decoration: none;
            user-select: none;
            padding: 1em 2em;
            outline: none;
            border: 2px solid #c1034a;
            border-radius: 1px;
            transition: 0.2s;
            word-break: break-all;
            &:hover {
            }
            &:nth-child(odd) {
            }
        }
    }
</style>
