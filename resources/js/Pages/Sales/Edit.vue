<template>
    <AppLayout title="POS">

        <template #header>
            <h2 class="font-semibold text-xl leading-tight">
                Editar venta
            </h2>
        </template>

        <div class="py-12">
            <div class="container mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col-reverse lg:flex-row gap-x-4 gap-y-4">
                    <div class="w-full lg:w-2/3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="px-8 py-10">
                            <h1 class="text-2xl font-medium mb-2">
                                Editar venta
                            </h1>

                            <Sale :sale></Sale>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="px-8 py-10">
                            <h1 class="text-2xl font-medium mb-2">
                                Productos
                            </h1>

                            <ByCategoriesProducts :categories="categories" :tags="tags"></ByCategoriesProducts>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import Sale from '@/Pages/Sales/Sale.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import axios from 'axios';
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Dinero from 'dinero.js';
import ByCategoriesProducts from './ByCategoriesProducts.vue';

defineProps({
    sale: Object,
    categories: Array,
    tags: Array,
});
</script>
