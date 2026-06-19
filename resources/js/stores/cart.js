import { defineStore } from 'pinia';
import axios from 'axios';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
        count: 0,
        total: 0,
        couponCode: null,
        discount: 0,
        loading: false,
    }),
    actions: {
        async fetchCart() {
            try {
                const { data } = await axios.get('/cart', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                this.items = data.items || [];
                this.count = data.items?.reduce((sum, item) => sum + item.quantity, 0) || 0;
                this.total = data.total || 0;
                this.couponCode = data.coupon_code;
                this.discount = data.discount || 0;
            } catch { /* ignore */ }
        },
        async addItem(productId, quantity = 1) {
            this.loading = true;
            try {
                await axios.post('/cart/add', { product_id: productId, quantity });
                await this.fetchCart();
            } finally { this.loading = false; }
        },
        async updateItem(itemId, quantity) {
            try {
                await axios.patch(`/cart/${itemId}`, { quantity });
                await this.fetchCart();
            } catch { /* ignore */ }
        },
        async removeItem(itemId) {
            try {
                await axios.delete(`/cart/${itemId}`);
                await this.fetchCart();
            } catch { /* ignore */ }
        },
        async applyCoupon(code) {
            try {
                await axios.post('/cart/coupon', { code });
                await this.fetchCart();
                return true;
            } catch { return false; }
        },
        async removeCoupon() {
            try {
                await axios.delete('/cart/coupon');
                await this.fetchCart();
            } catch { /* ignore */ }
        }
    }
});
