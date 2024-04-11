<template>
    <v-data-table :headers="headers" :items="items" class="elevation-1">
        <template v-slot:items="props">
            <td>{{ props.item.name }}</td>
            <td>{{ props.item.age }}</td>
            <td>{{ props.item.email }}</td>
        </template>
    </v-data-table>
</template>

<script>
import { defineComponent } from 'vue';
import { VDataTable } from 'vuetify/lib';

export default defineComponent({
    components: {
        VDataTable,
    },
    data() {
        return {
            headers: [
                { text: 'Name', value: 'name' },
                { text: 'Age', value: 'age' },
                { text: 'Email', value: 'email' },
            ],
            items: [
                { name: 'John Doe', age: 30, email: 'john@example.com' },
                { name: 'Jane Smith', age: 25, email: 'jane@example.com' },
                // Добавьте другие записи
            ],
        };
    },
});
</script>
