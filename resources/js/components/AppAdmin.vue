<template @click="test123">
    <div>
        <h3 class="text-center">Заказы из {{ category.title }}</h3>
        <vue-good-table
            :columns="columns"
            :rows="rows"
            theme="polar-bear"
            :search-options="{
                enabled: true
            }"
        />
    </div>
</template>

<script>
    import moment from 'moment';
export default {
    props: {
        category: Object
    },
    components: {
    },
    data: () => ({
        columns: [
            {
                label: 'Дата',
                field: 'createdAt',
                type: 'date',
                formatFn: function (value) {
                    return value != null ? moment(value).format('DD.MM.YYYY') : null
                }
            },
            {
                label: 'Название',
                field: 'itemTitle',
            },
            {
                label: 'Цена',
                field: 'itemPrice',
            },
            {
                label: 'Кол-во',
                field: 'itemCnt',
                type: 'number'
            },
            {
                label: 'Сумма',
                field: 'orderTotal',
                type: 'number'
            },
            {
                label: 'Имя',
                field: 'contactName',
            },
            {
                label: 'Телефон',
                field: 'contactPhone',
                sortable: false
            },
        ],
    }),
    computed: {
        rows() {
            // console.log(this.category);
            return this.category.order_items.map((element, index) => {
                // console.log(element);
                const { item, order } = element;
                return {
                    'createdAt': element.created_at,
                    'itemTitle': item.title,
                    'itemPrice': item.price,
                    'itemCnt': element.cnt,
                    'orderTotal': item.price * element.cnt,
                    'contactName': order ? order.name : '',
                    'contactPhone': order ? order.phone : ''
                };
            });
        },
    },
    methods: {
    }
}
</script>

<style scoped>
</style>
