/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/billing.css';

// start the Stimulus application
import './bootstrap';

// Vue import
import { createApp } from 'vue';

// Vue componets
import BlButton from './components/billing/button.vue';

createApp({
    delimiters: ['[[', ']]'],
    components: {
        BlButton
    }
})
  .mount('#app')
