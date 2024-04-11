<template>
    <div class="menu mt-3">
        <div v-if="parentTitle.length > 0" class="row justify-content-between mb-3">
            <div class="col-12 parent-title">
                {{ parentTitle }}
            </div>
        </div>
        <ul v-if="categories.length > 0">
            <router-link
                v-for="category in categories"
                :key="category.route"
                :to="`/category/${category.slug}`"
                v-slot="{ route, isExactActive, navigate }"
                :custom="true">
                <li :class="isExactActive ? 'active' : ''">
                    <a :href="route.fullPath"
                       @click="navigate">
                        <div class="img-container">
                            <img :src="getImg(category)" class="img-responsive" alt="">
                        </div>
                        <div class="label-block">
                            <label>{{ category.title }}</label>
                        </div>
                    </a>
                </li>
            </router-link>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "MainMenu",
        props: ['categories', 'parentTitle'],
        methods: {
            getImg(category) {
                return category.img === null ? '/assets/no-image.png' : category.img;
            }
        }
    }
</script>

<style lang="scss">
    .menu {
        .parent-title {
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
            background-color: #000;
        }
        ul {
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            //align-items: stretch;
            gap: .5rem;

            @include media-breakpoint-down(sm) {
                margin-left: -15px;
                margin-right: -15px;
            }

            li {
                flex: 0 0 calc(16.66% - 20px);
                @include media-breakpoint-down(md) {
                    flex: 0 0 calc(20% - 20px);
                }
                @include media-breakpoint-down(sm) {
                    flex: 0 0 31%;
                }
                list-style: none;
                //height: 260px;
                @include media-breakpoint-down(md) {
                    //height: 210px;
                }
                @include media-breakpoint-down(sm) {
                    //height: 200px;
                }
                @include media-breakpoint-down(xs) {
                    //height: 195px;
                }
                a {
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start;
                    height: 100%;
                    margin: 0 auto;
                    background: #fff;
                    border-radius: 12px;
                    text-align: center;
                    color: #000;
                    padding: 15px 13px 5px 13px;
                    @include media-breakpoint-down(sm) {
                        padding: 9px 25px 12px 25px;
                    }

                    &:hover {
                        text-decoration: none;
                    }

                    .img-container {
                        //padding: 13px 20px 0 20px;
                        @include media-breakpoint-down(sm) {
                            //padding-left: 10px;
                            //padding-right: 10px;
                        }
                        img {
                            max-width: 100%;
                            //width: 160px;
                            //width: auto;

                            @include media-breakpoint-down(md) {
                                //width: 110px;
                            }

                            @include media-breakpoint-down(sm) {
                                //max-width: 100%;
                            }
                        }

                    }

                    .label-block {
                        margin-top: 5px;
                        display: flex;
                        height: inherit;
                        align-items: center;
                        justify-content: center;
                    }

                    label {
                        text-align: center;
                        word-break: break-word;
                        //margin-top: 10px;
                        //padding: 6px;
                        font-size: 14px;
                        font-weight: bold;
                        margin-bottom: 0;
                        @include media-breakpoint-down(sm) {
                            font-size: 10px;
                        }
                        &:hover {
                            color: #1b1e21;
                            cursor: pointer;
                        }
                    }
                }
            }
        }
    }
</style>
