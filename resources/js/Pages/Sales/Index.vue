<template>
    <AppLayout title="POS">

        <template #header>
            <h2 class="font-semibold text-xl leading-tight">
                Ventas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-8 py-10">
                        <h1 class="text-2xl font-medium mb-2">
                            Ventas
                        </h1>

                        <table class="table-default">
                            <thead>
                                <tr>
                                    <th class="">Id</th>
                                    <th class="text-left">Fecha</th>
                                    <th class="text-left">Cliente</th>
                                    <th class="text-left">Total</th>
                                    <th class="text-left"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <TransitionGroup name="list">
                                    <tr v-for="sale in sales.data" :key="sale.id">
                                        <td class="text-center">{{ sale.id }}</td>
                                        <td>{{ DateTime.fromISO(sale.created_at).toLocaleString(DateTime.DATETIME_MED)
                                            }}</td>
                                        <td>{{ sale.client.name }}</td>
                                        <td>{{ Dinero({ amount: sale.total }).toFormat('$0.00') }}</td>
                                        <td>
                                            <div class="space-x-1">
                                                <PrimaryButton :href="route('sales.edit', { sale: sale.id })">
                                                    Editar
                                                </PrimaryButton>
                                                <DangerButton @click="tryDelete(sale.id)">
                                                    Eliminar
                                                </DangerButton>
                                            </div>
                                        </td>
                                    </tr>
                                </TransitionGroup>
                            </tbody>
                        </table>

                        <Paginator :rows="50" :totalRecords="sales.total"
                            @update:first="(page) => router.get(route('sales.index'), { page: page }, { preserveState: true })">
                        </Paginator>
                    </div>

                </div>
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import swal from 'sweetalert';
import Dinero from 'dinero.js';
import { DateTime } from "luxon";
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Paginator from 'primevue/paginator';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    sales: Object,
});

const tryDelete = (saleId) => {
    swal({
        title: "¿Está seguro?",
        text: "Una vez borrada esta venta, no se podrá recuperarla.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            router.delete(route('sales.delete', {sale: saleId}));
        }
    });
};

</script>
