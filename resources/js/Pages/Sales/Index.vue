<template>
    <AppLayout title="POS">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                                    <th class="text-left">Fecha</th>
                                    <th class="text-left">Cliente</th>
                                    <th class="text-left">Total</th>
                                    <th class="text-left"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <TransitionGroup name="list">
                                    <tr v-for="sale in sales.data" :key="sale.id">
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
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

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
