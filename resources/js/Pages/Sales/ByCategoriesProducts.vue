<template>
    <div>

        <div class="flex items-center gap-x-2">
            <div>
                <InputLabel>Línea</InputLabel>
                <Dropdown v-model="selectedCategory" :options="categoriesPool" option-label="name"
                    @update:model-value="getProducts"></Dropdown>
            </div>
            <div>
                <InputLabel>Grupo</InputLabel>
                <Dropdown v-model="selectedTag" :options="tagsPool" showClear @update:model-value="getProducts"
                    placeholder="Todos"></Dropdown>
            </div>
            <div>
                <ProgressSpinner v-show="loading" class="h-6 w-6" strokeWidth="8"></ProgressSpinner>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-3 gap-1">
            <button v-for="product in products" :key="product.id"
                @click="addingItem.show = true; addingItem.product = product;"
                class="p-5 bg-gray-300 hover:bg-gray-400 text-sm text-center border rounded transition">
                <div>{{ product.name }}</div>
                <div>Stock: {{ product.stock }}</div>
            </button>
        </div>

        <Dialog v-model:visible="addingItem.show" modal :header="addingItem.product?.name" :style="{ width: '25rem' }">
            <form @submit.prevent="emitAddItem(addingItem)">
                <InputLabel>Cantidad</InputLabel>
                <TextInput autofocus type="number" v-model.number="addingItem.quantity" class="w-full" @keydown="allowOnlyInteger"></TextInput>
                <PrimaryButton class="mt-5" type="submit">Añadir</PrimaryButton>
            </form>
        </Dialog>

    </div>
</template>

<script setup>
import Dialog from 'primevue/dialog';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import Dropdown from 'primevue/dropdown';
import { computed, inject, nextTick, onMounted, reactive, ref } from 'vue';
import emitter from 'tiny-emitter/instance';
import ProgressSpinner from 'primevue/progressspinner';
import swal from 'sweetalert';
import TextInput from '@/Components/TextInput.vue';
import {allowOnlyInteger} from '@/Composables/useKeyValidation.js';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    categories: Array,
    tags: Array,
});

const loading = ref(false);
const nullCategory = { name: '--Todas--', id: null };
const selectedCategory = ref(nullCategory);
const selectedTag = ref(null);
const categoriesPool = computed(() => [nullCategory].concat(props.categories));
const tagsPool = computed(() => props.tags);
const products = ref([]);
const addingItem = reactive({
    product: null,
    show: false,
    quantity: 1,
});

onMounted(() => {
    getProducts();
});

emitter.on('sale-completed-event', function (product) {
    getProducts();
});

const getProducts = () => {
    loading.value = true;
    axios.get(route('products.index', { category_id: selectedCategory.value.id, tag: selectedTag.value }))
        .then(response => {
            products.value = response.data;
            loading.value = false;
        });
};

const emitAddItem = (item) => {
    emitter.emit('add-sale-item-event', item);
    addingItem.show = false;
    addingItem.product = null;
    addingItem.quantity = 1;
};
</script>
