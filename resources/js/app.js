/**
 * app.js - Main application entry point
 * Sets up Vue components for our end-to-end encrypted messaging application
 */

import './bootstrap';
import { createApp } from 'vue';

// Import SweetAlert2 and custom theme
import 'sweetalert2/dist/sweetalert2.min.css';
import '../css/sweetalert2-custom.css';

// Import our Vue components
import LoginComponent from './components/auth/Login.vue';
import RegisterComponent from './components/auth/Register.vue';
import KeyStatusComponent from './components/KeyStatus.vue';
import MessagingComponent from './components/Messaging.vue';
import MobileMessagingComponent from './components/MobileMessaging.vue';
import AccountComponent from './components/Account.vue';

// Initialize Vue apps
window.addEventListener('load', () => {
    // Login component
    const loginElement = document.getElementById('login-app');
    if (loginElement) {
        createApp(LoginComponent).mount('#login-app');
    }
    
    // Register component for new user registration with key generation
    const registerElement = document.getElementById('register-app');
    if (registerElement) {
        createApp(RegisterComponent).mount('#register-app');
    }
    
    // Key status component for the settings page
    const keyStatusElement = document.getElementById('key-status-app');
    if (keyStatusElement) {
        createApp(KeyStatusComponent).mount('#key-status-app');
    }
    
    // Account component for account settings and deletion
    const accountElement = document.getElementById('account-app');
    if (accountElement) {
        createApp(AccountComponent).mount('#account-app');
    }
    
    // Messaging component for the messages page
    const messagingElement = document.getElementById('messaging-app');
    if (messagingElement) {
        createApp(MessagingComponent).mount('#messaging-app');
    }
    
    // Mobile messaging component for the mobile messages page
    const mobileMessagingElement = document.getElementById('mobile-messaging-app');
    if (mobileMessagingElement) {
        createApp(MobileMessagingComponent).mount('#mobile-messaging-app');
    }
});
