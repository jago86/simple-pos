<template>
    <div>
        <InputLabel>Línea</InputLabel>
        <div class="flex items-center gap-x-2">
            <div>
                <Dropdown v-model="selectedCategory" :options="categoriesPool" option-label="name"
                    @update:model-value="getProducts"></Dropdown>
            </div>
            <div>
                <ProgressSpinner v-show="loading" class="h-6 w-6" strokeWidth="8"></ProgressSpinner>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-3 gap-1">
            <button v-for="product in products" :key="product.id" @click="emitter.emit('add-sale-item-event', product)"
                class="p-5 bg-gray-300 hover:bg-gray-400 text-sm text-center border rounded transition">
                <div>{{ product.name }}</div>
                <div>Stock: {{ product.stock }}</div>
            </button>
        </div>
    </div>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import Dropdown from 'primevue/dropdown';
import { computed, inject, nextTick, onMounted, ref } from 'vue';
import emitter from 'tiny-emitter/instance';
import ProgressSpinner from 'primevue/progressspinner';

const props = defineProps({
    categories: Array,
});

const loading = ref(false);
const nullCategory = { name: '--Todas--', id: null };
const selectedCategory = ref(nullCategory);
const categoriesPool = computed(() => [nullCategory].concat(props.categories));
const products = ref([]);

onMounted(() => {
    getProducts();
});

emitter.on('sale-completed-event', function (product) {
    getProducts();
});

const getProducts = () => {
    loading.value = true;
    axios.get(route('products.index', { category_id: selectedCategory.value.id }))
        .then(response => {
            products.value = response.data;
            loading.value = false;
        });
};
</script>