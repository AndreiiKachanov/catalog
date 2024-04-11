<template>
    <h3 class="h text-center mb-4">Контактная информация</h3>
        <div class="row">
            <div
                class="col-sm-10 offset-sm-1 offset-md-0 col-md-6 col-lg-4"
                v-for="setting in settings"
                :key="setting.prop_key"
            >
                <div class="contact_info_item d-flex flex-row align-items-center justify-content-start">
                    <div class="contact_info_image">
                        <i
                            :class="`${setting.fa_icon} fa-2x`"
                            aria-hidden="true">
                        </i>
                    </div>
                    <div class="contact_info_content">
                        <div class="contact_info_title">{{ setting.title }}</div>
                        <div class="contact_info_text">
                            <a
                                v-if="this.isValidUrl(setting.prop_value)"
                                target="_blank"
                                :href="`${setting.prop_value}`"
                            >
                                {{ setting.prop_value }}
                            </a>
                            <a v-else-if="setting.prop_key === 'phone_number'"
                               :href="`tel:${setting.prop_value}`"
                            >
                                {{ setting.prop_value }}
                            </a>
                            <span v-else>
                                {{ setting.prop_value }}
                           </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import { mapGetters } from "vuex";
    export default {
        name: "Contacts",
        components: {
        },
        computed: {
            ...mapGetters('settings', {
                allSettings: 'allSettings'
            }),
            settings() {
                return this.allSettings.filter(item => item.fa_icon !== null);
            }
        },
        methods: {
            isValidUrl(str) {
                try {
                    new URL(str);
                    return true;
                } catch (error) {
                    return false;
                }
            },
        }
    }
</script>

<style lang="scss">
    .contact_info
    {
        width: 100%;
        padding-top: 70px;
    }
    .contact_info_item {
        margin-bottom: 20px;
        min-height: 100px;
        border: solid 1px #e8e8e8;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
        padding-left: 25px;
        padding-right: 15px;
        background-color: #fff;

    }
    .contact_info_image
    {
        width: 35px;
        height: 35px;
        text-align: center;
        i {
            //color: #c1034a;
            color: red;
        }
    }
    .contact_info_image img
    {
        max-width: 100%;
    }
    .contact_info_content
    {
        padding: 20px 0 20px 17px;
    }
    .contact_info_title
    {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .contact_info_text
    {
        font-size: 12px;
        color: rgba(0,0,0,0.5);

        span {
            word-break: break-all;
        }
    }
</style>
