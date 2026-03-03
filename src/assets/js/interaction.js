/**
 * DREAMSPACE - Application Specific Interactions
 */

// Page-specific functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize based on current page
    const page = getUrlParameter('page') || 'home';
    initPage(page);
});

/**
 * Initialize page-specific features
 */
function initPage(page) {
    switch(page) {
        case 'login':
            initLoginPage();
            break;
        case 'register':
            initRegisterPage();
            break;
        case 'inventory':
            initInventoryPage();
            break;
        case 'items':
            initItemsPage();
            break;
        case 'trades':
            initTradesPage();
            break;
    }
}

/**
 * Login page interactions
 */
function initLoginPage() {
    const form = document.querySelector('.auth-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const username = document.getElementById('username')?.value;
            const password = document.getElementById('password')?.value;

            if (!username || !password) {
                e.preventDefault();
                showNotification('Vul alle velden in', 'warning');
            }
        });
    }
}

/**
 * Register page interactions
 */
function initRegisterPage() {
    const passwordInput = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const form = document.querySelector('.auth-form');

    if (passwordInput && passwordConfirm) {
        // Real-time password strength indicator
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updatePasswordStrengthIndicator(strength);
        });

        // Password match checker
        passwordConfirm.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.classList.add('mismatch');
            } else {
                this.classList.remove('mismatch');
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            const pass = passwordInput?.value;
            const passConfirm = passwordConfirm?.value;

            if (pass !== passConfirm) {
                e.preventDefault();
                showNotification('Wachtwoorden komen niet overeen', 'error');
            }
        });
    }
}

/**
 * Check password strength
 */
function checkPasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[!@#$%^&*()_+\-=\[\]{};:'"<>?\/\\|`~]/.test(password)) strength++;

    return strength;
}

/**
 * Update password strength indicator
 */
function updatePasswordStrengthIndicator(strength) {
    let indicator = document.querySelector('.password-strength-bar');
    if (!indicator) {
        const passwordGroup = document.querySelector('#password')?.parentElement;
        if (passwordGroup) {
            indicator = document.createElement('div');
            indicator.className = 'password-strength-bar';
            indicator.style.cssText = 'height: 4px; background: #ddd; border-radius: 2px; margin-top: 8px; overflow: hidden;';
            passwordGroup.appendChild(indicator);
        }
    }

    if (indicator) {
        const colors = ['#dc3545', '#ffc107', '#17a2b8', '#28a745', '#007bff'];
        const width = (strength / 5) * 100;
        indicator.style.background = colors[strength - 1] || '#ddd';
        indicator.style.width = width + '%';
    }
}

/**
 * Inventory page interactions
 */
function initInventoryPage() {
    // Item deletion
    const deleteButtons = document.querySelectorAll('.btn-delete-item');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const itemName = this.dataset.itemName || 'item';
            confirmAction(
                `Weet je zeker dat je ${itemName} wilt verwijderen?`,
                () => deleteItem(this)
            );
        });
    });

    // Item sorting
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            // In production, this would trigger a page reload or AJAX call
            window.location.href = `?page=inventory&sort=${this.value}`;
        });
    }

    // Item filtering
    const filterInputs = document.querySelectorAll('.filter-input');
    filterInputs.forEach(input => {
        input.addEventListener('change', debounce(() => {
            applyFilters();
        }, 300));
    });
}

/**
 * Items catalog page interactions
 */
function initItemsPage() {
    // Search functionality
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            searchItems(this.value);
        }, 300));
    }

    // Filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Apply filter
        });
    });
}

/**
 * Trades page interactions
 */
function initTradesPage() {
    // Accept trade button
    const acceptButtons = document.querySelectorAll('.btn-accept-trade');
    acceptButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            confirmAction('Ben je zeker dat je deze trade accepteert?', () => {
                acceptTrade(this);
            });
        });
    });

    // Reject trade button
    const rejectButtons = document.querySelectorAll('.btn-reject-trade');
    rejectButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            confirmAction('Ben je zeker dat je deze trade afwijst?', () => {
                rejectTrade(this);
            });
        });
    });

    // Cancel trade button
    const cancelButtons = document.querySelectorAll('.btn-cancel-trade');
    cancelButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            confirmAction('Ben je zeker dat je deze trade wilt annuleren?', () => {
                cancelTrade(this);
            });
        });
    });
}

/**
 * Delete item from inventory
 */
function deleteItem(element) {
    const itemId = element.dataset.itemId;
    console.log('Deleting item:', itemId);
    showNotification('Item verwijderd', 'success');
    // In production, make AJAX call to delete item
    element.closest('.item-row')?.remove();
}

/**
 * Accept a trade
 */
function acceptTrade(element) {
    const tradeId = element.dataset.tradeId;
    console.log('Accepting trade:', tradeId);
    showNotification('Trade geaccepteerd!', 'success');
    // In production, make AJAX call to accept trade
}

/**
 * Reject a trade
 */
function rejectTrade(element) {
    const tradeId = element.dataset.tradeId;
    console.log('Rejecting trade:', tradeId);
    showNotification('Trade afgewezen', 'info');
    // In production, make AJAX call to reject trade
}

/**
 * Cancel a trade
 */
function cancelTrade(element) {
    const tradeId = element.dataset.tradeId;
    console.log('Canceling trade:', tradeId);
    showNotification('Trade geannuleerd', 'info');
    // In production, make AJAX call to cancel trade
}

/**
 * Search items
 */
function searchItems(query) {
    console.log('Searching for:', query);
    // In production, make AJAX call with search query
}

/**
 * Apply filters
 */
function applyFilters() {
    console.log('Applying filters');
    // In production, gather all filter values and make AJAX call
}

// Expose functions globally if needed
window.deleteItem = deleteItem;
window.acceptTrade = acceptTrade;
window.rejectTrade = rejectTrade;
window.cancelTrade = cancelTrade;
window.searchItems = searchItems;
window.applyFilters = applyFilters;
