<template>
    <div>
        <div class="flex gap-x-2 mb-5">
            <div class="w-1/3">
                <div class="form-group">
                    <InputLabel>Cliente</InputLabel>
                    <Dropdown v-model="clientQuery" editable :options="clients" @change="handleChangeClientQuery"
                        option-label="name" placeholder="Seleccione un cliente">
                    </Dropdown>
                    <div v-if="saleForm.errors.client_id" class="text-sm text-rose-500">{{ saleForm.errors.client_id }}
                    </div>
                </div>
            </div>
            <div class="w-1/3">
                <InputLabel>Producto</InputLabel>
                <Dropdown v-model="productQuery" editable :options="products" @change="handleChangeProductQuery"
                    placeholder="Seleccione un producto" option-label="name"></Dropdown>
                <div v-if="saleForm.errors.items" class="text-sm text-rose-500">{{ saleForm.errors.items }}</div>
            </div>
        </div>

        <table class="table-default">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <TransitionGroup name="list">
                    <tr v-for="(item, index) in saleForm.items" :key="index">
                        <td class="">{{ item.product.name }}</td>
                        <td class="w-20">
                            <TextInput class="w-20 text-center" v-model="item.quantity" @keydown="allowOnlyInteger"
                                @change="handleQuantityChange(item)"></TextInput>
                            <div v-if="saleForm.errors[`items.${index}.quantity`]" class="text-sm text-rose-500">{{
                                saleForm.errors[`items.${index}.quantity`] }}</div>
                        </td>
                        <td class="text-center">{{ item.price.toFormat('$0.00') }}</td>
                        <td class="w-20">
                            <InputIcon class="w-20" v-model="item.discountPercentage" @keydown="allowOnlyInteger"
                                type="text" placeholder="Descuento">
                                <ReceiptPercentIcon class="w-6 h-6"></ReceiptPercentIcon>
                            </InputIcon>
                            <div v-if="saleForm.errors[`items.${index}.discount_percentage`]"
                                class="w-20 text-sm text-rose-500">{{
                                saleForm.errors[`items.${index}.discount_percentage`] }}</div>
                        </td>
                        <td class="text-center">{{ item.total().toFormat('$0.00') }}</td>
                        <td>
                            <SecondaryButton @click="removeItem(item)">
                                <TrashIcon class="h-4 w-4"></TrashIcon>
                            </SecondaryButton>
                        </td>
                    </tr>
                </TransitionGroup>
            </tbody>
        </table>
        <div>Subtotal: {{ saleForm.subtotal().toFormat('$0.00') }}</div>
        <div>Iva: {{ saleForm.tax().toFormat('$0.00') }}</div>
        <div>Total: {{ saleForm.total().toFormat('$0.00') }}</div>

        <div class="space-x-2 mt-2">
            <PrimaryButton @click="save">Guardar</PrimaryButton>
            <SecondaryButton v-if="props.sale !== null" :href="route('sales.index')">Volver</SecondaryButton>
            <SecondaryButton v-else @click="resetSale">Cancelar</SecondaryButton>
        </div>
    </div>
</template>

<script setup>
import Dropdown from 'primevue/dropdown';
import InputLabel from '@/Components/InputLabel.vue';
import InputIcon from '@/Components/InputIcon.vue';
import TextInput from '@/Components/TextInput.vue';
import { ReceiptPercentIcon, TrashIcon } from "@heroicons/vue/16/solid";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Dinero from 'dinero.js';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const productQuery = ref('');
const clientQuery = ref('');
const products = ref([]);
const clients = ref([]);

const props = defineProps({
    sale: {
        type: Object,
        default: null,
    },
});

onMounted(() => {
    if (props.sale) {
        loadSale();
    }
});

const saveMethod = computed(() => props.sale ? 'put' : 'post');
const saveRoute = computed(() => props.sale ? route('sales.update', { sale: props.sale.id }) : route('pos.store'))

const loadSale = () => {
    clients.value = [props.sale.client];
    clientQuery.value = props.sale.client;
    saleForm.client_id = props.sale.client.id;
    props.sale.details.forEach(detail => {
        saleForm.items.push(new SaleItem(detail.product, detail.quantity, detail.product.price));
    });
};

const saleForm = useForm({
    client_id: null,
    items: [],

    subtotal() {
        let subtotal = Dinero({ amount: 0 });

        this.items.forEach(saleItem => {
            subtotal = subtotal.add(saleItem.total());
        });
        return subtotal;
    },

    tax() {
        return this.subtotal().percentage(12)
    },

    total() {
        return this.subtotal().add(this.tax());
    }
});

const handleChangeProductQuery = () => {
    if (productQuery.value.id) {
        addSaleItem(productQuery.value);
        resetProduct();
        return;
    }

    searchProducts();
};

const handleChangeClientQuery = () => {
    if (clientQuery.value.id) {
        saleForm.client_id = clientQuery.value.id;
        return;
    }

    searchClients();
};

const searchProducts = _.debounce(() => {
    axios.get(route('products.index', { name: productQuery.value }))
        .then(response => {
            products.value = response.data
        });
}, 300);

const searchClients = _.debounce(() => {
    axios.get(route('clients.index', { name: clientQuery.value }))
        .then(response => {
            clients.value = response.data
        });
}, 300);

const addSaleItem = (product) => {
    let saleItem = saleForm.items.find(item => item.product.id == product.id);

    if (saleItem) {
        saleItem.add();
        return;
    }

    saleForm.items.push(new SaleItem(product, 1, product.price));
};

const handleQuantityChange = (item) => {
    if (item.quantity < 0) {
        removeItem(item);
    }
};

const resetProduct = () => {
    productQuery.value = '';
};

const save = () => {
    saleForm.transform(data => ({
        ...data,
        tax_id: 1,
        items: data.items.map(item => ({
            product_id: item.product.id,
            quantity: item.quantity,
            price: item.price.toFormat('0.00'),
            discount_percentage: item.discountPercentage,
        }))
    }))[saveMethod.value](saveRoute.value, {
        onSuccess: () => resetSale(),
    })
};

const resetSale = () => {
    saleForm.reset();
    clientQuery.value = '';
};

const removeItem = (itemToRemove) => {
    let index = saleForm.items.findIndex(item => item.product.id == itemToRemove.product.id);

    if (index >= 0) {
        saleForm.items.splice(index, 1);
    }
};

class SaleItem {
    discountPercentage = 0;
    constructor(product, quantity, price) {
        this.product = product;
        this.quantity = quantity;
        this.price = Dinero({ amount: price });
    }

    add() {
        this.quantity++;
    }

    total() {
        let discount = this.price.multiply(this.quantity).percentage(this.discountPercentage);
        return this.price.multiply(this.quantity).subtract(discount);
    }
};

const allowOnlyInteger = (event) => {
    // Permite teclas de navegación sin restricciones
    const allowedKeys = ['ArrowLeft', 'ArrowRight', 'Tab', 'Backspace', 'Delete'];
    if (allowedKeys.includes(event.key)) return;

    // Crea una copia del valor actual del input, incluyendo el nuevo carácter
    const proposedValue = event.target.value + event.key;
    // Verifica si el valor propuesto es un número decimal válido
    const isInteger = /^-?\d*$/.test(proposedValue);

    if (!isInteger) {
        // Previene la adición del carácter si el resultado no es un número decimal
        event.preventDefault();
    }
}

</script>
