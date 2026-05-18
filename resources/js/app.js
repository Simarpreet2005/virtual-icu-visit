import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Real-time notifications
if (window.Echo) {
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    if (userId) {
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                // Dispatch event for Alpine.js to handle
                window.dispatchEvent(new CustomEvent('new-notification', { 
                    detail: notification 
                }));
                
                // Update notification count if possible
                const countBadge = document.querySelector('[data-notification-count]');
                if (countBadge) {
                    const currentCount = parseInt(countBadge.innerText.replace('+', '')) || 0;
                    countBadge.innerText = (currentCount + 1) > 9 ? '9+' : (currentCount + 1);
                    countBadge.classList.remove('hidden');
                }
            });
    }
}
