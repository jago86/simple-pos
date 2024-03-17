import { useForm } from '@inertiajs/vue3';
import Dinero from 'dinero.js';
import _ from 'lodash';
import { ref } from 'vue';

export function useSale() {

    return useForm({
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
    })
};

export function usePOS() {

    const productQuery = ref('');
    const products = ref([]);

    const searchProducts = _.debounce(() => {

        axios.get(route('products.index', { name: productQuery.value }))
            .then(response => {
                products.value = response.data
            });
    }, 300);

    return { productQuery, products, searchProducts };
}


export const useSearchClients = _.debounce(() => {
    axios.get(route('clients.index', { name: clientQuery.value }))
        .then(response => {
            clients.value = response.data
        });
}, 300);
