/**
 * ToastService.js
 * Centralized service for displaying toast notifications using Sweetalert2
 */

import Swal from 'sweetalert2';

class ToastService {
    /**
     * Show a success toast notification
     * @param {string} message - The message to display
     */
    success(message) {
        this._showToast(message, 'success');
    }

    /**
     * Show an error toast notification
     * @param {string} message - The message to display
     */
    error(message) {
        this._showToast(message, 'error');
    }

    /**
     * Show an info toast notification
     * @param {string} message - The message to display
     */
    info(message) {
        this._showToast(message, 'info');
    }

    /**
     * Show a warning toast notification
     * @param {string} message - The message to display
     */
    warning(message) {
        this._showToast(message, 'warning');
    }

    /**
     * Internal method to show a toast with common configuration
     * @param {string} message - The message to display
     * @param {string} icon - The icon type ('success', 'error', 'info', 'warning')
     * @private
     */
    _showToast(message, icon) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }
}

export default new ToastService();