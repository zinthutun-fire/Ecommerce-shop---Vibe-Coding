import { defineStore } from 'pinia';
import axios from 'axios';

export const useWishlistStore = defineStore('wishlist', {
    state: () => ({
        items: [],
        loading: false,
    }),
    actions: {
        async toggle(productId) {
            try {
                const { data } = await axios.post('/wishlist/toggle', { product_id: productId });
                return data.wishlisted;
            } catch { return false; }
        },
        async fetch() {
            try {
                const { data } = await axios.get('/wishlist');
                this.items = data.wishlists || [];
            } catch { /* ignore */ }
        }
    }
});
