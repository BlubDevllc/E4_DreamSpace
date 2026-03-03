/**
 * DREAMSPACE - Main JavaScript
 * Global interactions and functionality
 */

// On page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DreamSpace loaded');
    initializeNavigation();
    initializeDropdowns();
});

/**
 * Initialize mobile navigation toggle
 */
function initializeNavigation() {
    const toggle = document.querySelector('.navbar-toggle');
    const menu = document.querySelector('.navbar-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            menu.classList.toggle('active');
        });

        // Close menu when a link is clicked
        const links = menu.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                menu.classList.remove('active');
            });
        });
    }
}

/**
 * Initialize dropdown menus
 */
function initializeDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (toggle && menu) {
            // Click to toggle on mobile
            toggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 991) {
                    e.preventDefault();
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                }
            });
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            dropdowns.forEach(dropdown => {
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.display = 'none';
                }
            });
        }
    });
}

/**
 * Show notification toast
 */
function showNotification(message, type = 'info', duration = 3000) {
    const notificationId = 'notification-' + Date.now();
    const notification = document.createElement('div');
    notification.id = notificationId;
    notification.className = 'notification toast-' + type;
    notification.innerHTML = `
        <div class="notification-content">
            <span>${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove();">×</button>
        </div>
    `;

    // Add to body
    if (!document.querySelector('.notification-container')) {
        const container = document.createElement('div');
        container.className = 'notification-container';
        document.body.appendChild(container);
    }

    document.querySelector('.notification-container').appendChild(notification);

    // Auto remove
    if (duration > 0) {
        setTimeout(() => {
            const elem = document.getElementById(notificationId);
            if (elem) elem.remove();
        }, duration);
    }

    return notificationId;
}

/**
 * Confirm dialog
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return '€' + parseFloat(amount).toFixed(2);
}

/**
 * Format date
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('nl-NL', options);
}

/**
 * Validate email
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Trim whitespace
 */
function trimInput(input) {
    return input.trim();
}

/**
 * Clone object
 */
function cloneObject(obj) {
    return JSON.parse(JSON.stringify(obj));
}

/**
 * Get URL parameter
 */
function getUrlParameter(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
}

/**
 * Scroll to element
 */
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

/**
 * Show loading spinner
 */
function showLoading(element) {
    element.innerHTML = '<span class="spinner"></span> Loading...';
    element.disabled = true;
}

/**
 * Hide loading spinner
 */
function hideLoading(element, text) {
    element.innerHTML = text;
    element.disabled = false;
}

/**
 * Check if element is in viewport
 */
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

/**
 * Debounce function
 */
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

/**
 * Local storage utilities
 */
const Storage = {
    set: function(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (e) {
            console.error('Storage error:', e);
        }
    },
    get: function(key) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : null;
        } catch (e) {
            console.error('Storage error:', e);
            return null;
        }
    },
    remove: function(key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.error('Storage error:', e);
        }
    },
    clear: function() {
        try {
            localStorage.clear();
        } catch (e) {
            console.error('Storage error:', e);
        }
    }
};

// Export functions if using modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showNotification,
        confirmAction,
        formatCurrency,
        formatDate,
        validateEmail,
        Storage
    };
}
