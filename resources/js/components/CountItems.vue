<template>
    <div class="input-group">
        <span class="input-group-prepend">
            <button @click="$emit('setCnt', this.countFromCart - 1)" type="button" class="btn btn-outline-secondary btn-number">
                <span class="fa fa-minus"></span>
            </button>
        </span>
        <input
            onkeyup="if(value <= 0) value = 1; if(value >= 65536) value = 65535;"
            type="number"
            class="pt-0 pb-0 form-control input-number"
            :value="countFromCart" style="text-align: center; height: auto"
            @change="onInput"
            :class="{ 'styled-input': hasMinOrder }"
        >
        <span class="input-group-append">
             <button @click="$emit('setCnt', this.countFromCart + 1)" type="button" class="btn btn-outline-secondary btn-number">
                <span class="fa fa-plus"></span>
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['countFromCart', 'hasMinOrder'],
        name: "CountCartItems",
        methods: {
            onInput(e) {
                let cnt = Math.max(1, parseInt(e.target.value));
                this.$emit('setCnt', cnt);
            },
        },
    }
</script>

<style lang="scss">
    .styled-input {
        box-shadow: inset 0 0 3px 1px rgb(255, 0, 0);
    }

    .input-group {
        @include media-breakpoint-down(xs) {

            span.input-group-prepend, span.input-group-append, input[type='text'] {
                button {
                    padding: 0.25rem 0.38rem
                }
            }

            button {
                line-height: 0 !important;
            }
            .fa {
                line-height: 0 !important;
                padding-bottom: 1px;
            }
            .fa:before {
                font-size: 8px;
            }
            input[type='number'] {
                padding-left: 0;
                padding-right: 0;
                line-height: 2.2 !important;
                font-size: 10px;
            }
        }
        @include media-breakpoint-down(lg) {
            span.input-group-prepend, span.input-group-append, input[type='text'] {
                button {
                    padding: 0.25rem 0.38rem
                }
            }
        }
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type='number'] {
        -moz-appearance: textfield;
    }
</style>
