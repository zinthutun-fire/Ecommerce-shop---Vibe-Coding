import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import CartDropdown from './components/CartDropdown.vue';
import CartPage from './components/CartPage.vue';
import WishlistButton from './components/WishlistButton.vue';
import ReviewForm from './components/ReviewForm.vue';
import StarRating from './components/StarRating.vue';
import SalesChart from './components/SalesChart.vue';

const app = createApp({});

app.use(createPinia());

app.component('cart-dropdown', CartDropdown);
app.component('cart-page', CartPage);
app.component('wishlist-button', WishlistButton);
app.component('review-form', ReviewForm);
app.component('star-rating', StarRating);
app.component('sales-chart', SalesChart);

const appEl = document.getElementById('app');
if (appEl) {
    app.mount(appEl);
}
