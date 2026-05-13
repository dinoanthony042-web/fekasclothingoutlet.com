import './bootstrap';

// Guest Cart Management with localStorage
class GuestCart {
    constructor() {
        this.storageKey = 'guest_cart';
    }

    getCart() {
        const cart = localStorage.getItem(this.storageKey);
        return cart ? JSON.parse(cart) : [];
    }

    addItem(productId, quantity = 1) {
        let cart = this.getCart();
        const existingItem = cart.find(item => item.product_id === productId);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({ product_id: productId, quantity });
        }
        
        localStorage.setItem(this.storageKey, JSON.stringify(cart));
        return this.getTotalQuantity();
    }

    removeItem(productId) {
        let cart = this.getCart();
        cart = cart.filter(item => item.product_id !== productId);
        localStorage.setItem(this.storageKey, JSON.stringify(cart));
        return this.getTotalQuantity();
    }

    getTotalQuantity() {
        const cart = this.getCart();
        return cart.reduce((total, item) => total + item.quantity, 0);
    }

    isInCart(productId) {
        const cart = this.getCart();
        return cart.some(item => item.product_id === productId);
    }
}

const guestCart = new GuestCart();
const authenticatedCart = new Set(); // Track authenticated user cart items

// Guest Wishlist Management with localStorage
class GuestWishlist {
    constructor() {
        this.storageKey = 'guest_wishlist';
    }

    getWishlist() {
        const wishlist = localStorage.getItem(this.storageKey);
        return wishlist ? JSON.parse(wishlist) : [];
    }

    addItem(productId) {
        let wishlist = this.getWishlist();
        if (!wishlist.includes(productId)) {
            wishlist.push(productId);
            localStorage.setItem(this.storageKey, JSON.stringify(wishlist));
        }
        return wishlist.length;
    }

    removeItem(productId) {
        let wishlist = this.getWishlist();
        wishlist = wishlist.filter(id => id !== productId);
        localStorage.setItem(this.storageKey, JSON.stringify(wishlist));
        return wishlist.length;
    }

    isInWishlist(productId) {
        const wishlist = this.getWishlist();
        return wishlist.includes(productId);
    }

    getTotalCount() {
        return this.getWishlist().length;
    }
}

const guestWishlist = new GuestWishlist();

// Handle Add to Cart
document.addEventListener('DOMContentLoaded', () => {
    const isAuthenticated = document.body.classList.contains('authenticated');
    console.log('User authenticated:', isAuthenticated);
    console.log('Body classes:', document.body.className);
    
    // Initialize authenticated cart state from current page
    if (isAuthenticated) {
        document.querySelectorAll('.remove-from-cart-form').forEach(form => {
            const productIdInput = form.querySelector('input[name="product_id"]');
            if (productIdInput) {
                authenticatedCart.add(parseInt(productIdInput.value));
            }
        });
    } else {
        // Initialize guest cart state
        const cart = guestCart.getCart();
        cart.forEach(item => {
            updateProductButton(item.product_id, true);
        });
    }

    // Initialize wishlist state
    if (isAuthenticated) {
        // For authenticated users, server-side state is already correct
        // But we could add additional initialization if needed
    } else {
        // Initialize guest wishlist state from localStorage
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            const productId = parseInt(btn.dataset.productId);
            if (guestWishlist.isInWishlist(productId)) {
                const icon = btn.querySelector('.wishlist-icon');
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500');
                btn.dataset.inWishlist = 'true';
            }
        });
    }
    
    // Add to Cart Form Submission
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const button = form.querySelector('.add-to-cart-btn');
            const originalText = button.textContent;
            const productIdInput = form.querySelector('input[name="product_id"]');
            const productId = parseInt(productIdInput.value);
            const container = form.closest('[data-product-container]') || form.parentElement;
            
            button.disabled = true;
            button.textContent = 'Adding...';
            
            try {
                // For guests, manage cart locally
                if (!isAuthenticated) {
                    const cartCount = guestCart.addItem(productId);
                    
                    button.textContent = '✓ Added';
                    setTimeout(() => {
                        updateCartCount(cartCount);
                        switchToRemoveButton(container, productId);
                    }, 800);
                    return;
                }

                // For authenticated users, send to server
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    button.textContent = '✓ Added';
                    authenticatedCart.add(productId);
                    console.log('Authenticated user - Cart count from server:', data.cart_count);
                    updateCartCount(data.cart_count);
                    
                    setTimeout(() => {
                        switchToRemoveButton(container, productId);
                    }, 800);
                } else {
                    button.textContent = originalText;
                    button.disabled = false;
                    alert(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Add to cart error:', error);
                button.textContent = originalText;
                button.disabled = false;
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Remove from Cart Form Submission
    document.querySelectorAll('.remove-from-cart-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const button = form.querySelector('.remove-from-cart-btn');
            const originalText = button.textContent;
            const productIdInput = form.querySelector('input[name="product_id"]');
            const productId = parseInt(productIdInput.value);
            const container = form.closest('[data-product-container]') || form.parentElement;
            
            button.disabled = true;
            button.textContent = 'Removing...';
            
            try {
                // For guests, manage cart locally
                if (!isAuthenticated) {
                    const cartCount = guestCart.removeItem(productId);
                    
                    button.textContent = '✓ Removed';
                    setTimeout(() => {
                        updateCartCount(cartCount);
                        switchToAddButton(container, productId);
                    }, 800);
                    return;
                }

                // For authenticated users, send to server
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    button.textContent = '✓ Removed';
                    authenticatedCart.delete(productId);
                    updateCartCount(data.cart_count);
                    
                    setTimeout(() => {
                        switchToAddButton(container, productId);
                    }, 800);
                } else {
                    button.textContent = originalText;
                    button.disabled = false;
                    alert(data.message || 'Failed to remove from cart');
                }
            } catch (error) {
                console.error('Remove from cart error:', error);
                button.textContent = originalText;
                button.disabled = false;
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Function to switch from Add to Remove button
    function switchToRemoveButton(container, productId) {
        const addForm = container.querySelector('.add-to-cart-form');
        if (!addForm) return;
        
        const removeForm = document.createElement('form');
        removeForm.method = 'post';
        removeForm.action = '/cart';
        removeForm.classList.add('mt-3', 'remove-from-cart-form');
        removeForm.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="product_id" value="${productId}">
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition remove-from-cart-btn">Remove from Cart</button>
        `;
        
        addForm.replaceWith(removeForm);
        
        // Add event listener to new remove form
        removeForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const button = removeForm.querySelector('.remove-from-cart-btn');
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Removing...';
            
            try {
                if (!isAuthenticated) {
                    const cartCount = guestCart.removeItem(productId);
                    button.textContent = '✓ Removed';
                    setTimeout(() => {
                        updateCartCount(cartCount);
                        switchToAddButton(container, productId);
                    }, 800);
                    return;
                }

                const response = await fetch(removeForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: new FormData(removeForm)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    button.textContent = '✓ Removed';
                    authenticatedCart.delete(productId);
                    updateCartCount(data.cart_count);
                    
                    setTimeout(() => {
                        switchToAddButton(container, productId);
                    }, 800);
                } else {
                    button.textContent = originalText;
                    button.disabled = false;
                    alert(data.message || 'Failed to remove from cart');
                }
            } catch (error) {
                console.error('Remove from cart error:', error);
                button.textContent = originalText;
                button.disabled = false;
                alert('An error occurred. Please try again.');
            }
        });
    }

    // Function to switch from Remove to Add button
    function switchToAddButton(container, productId) {
        const removeForm = container.querySelector('.remove-from-cart-form');
        if (!removeForm) return;
        
        const addForm = document.createElement('form');
        addForm.method = 'post';
        addForm.action = '/cart';
        addForm.classList.add('mt-3', 'add-to-cart-form');
        addForm.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="product_id" value="${productId}">
            <button type="submit" class="w-full bg-slate-900 text-white py-2 rounded-lg text-sm font-semibold hover:bg-slate-800 transition add-to-cart-btn">Add to Cart</button>
        `;
        
        removeForm.replaceWith(addForm);
        
        // Re-attach event listener to new add form
        addForm.addEventListener('submit', handleAddToCart);
    }

    function handleAddToCart(e) {
        e.preventDefault();
        
        const form = this;
        const button = form.querySelector('.add-to-cart-btn');
        const originalText = button.textContent;
        const productIdInput = form.querySelector('input[name="product_id"]');
        const productId = parseInt(productIdInput.value);
        const container = form.closest('[data-product-container]') || form.parentElement;
        
        button.disabled = true;
        button.textContent = 'Adding...';
        
        (async () => {
            try {
                if (!isAuthenticated) {
                    const cartCount = guestCart.addItem(productId);
                    button.textContent = '✓ Added';
                    setTimeout(() => {
                        updateCartCount(cartCount);
                        switchToRemoveButton(container, productId);
                    }, 800);
                    return;
                }

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    button.textContent = '✓ Added';
                    authenticatedCart.add(productId);
                    updateCartCount(data.cart_count);
                    
                    setTimeout(() => {
                        switchToRemoveButton(container, productId);
                    }, 800);
                } else {
                    button.textContent = originalText;
                    button.disabled = false;
                    alert(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Add to cart error:', error);
                button.textContent = originalText;
                button.disabled = false;
                alert('An error occurred. Please try again.');
            }
        })();
    }

    function updateProductButton(productId, isInCart) {
        const article = document.querySelector(`article[data-product-id="${productId}"]`);
        if (!article) return;
        
        const form = article.querySelector('.add-to-cart-form, .remove-from-cart-form');
        if (!form) return;
        
        if (isInCart && form.classList.contains('add-to-cart-form')) {
            switchToRemoveButton(article, productId);
        } else if (!isInCart && form.classList.contains('remove-from-cart-form')) {
            switchToAddButton(article, productId);
        }
    }

    // Wishlist Toggle
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            const productId = parseInt(btn.dataset.productId);
            const isInWishlist = btn.dataset.inWishlist === 'true';
            const icon = btn.querySelector('.wishlist-icon');
            
            try {
                if (isInWishlist) {
                    // Remove from wishlist
                    if (!isAuthenticated) {
                        // For guests, manage wishlist locally
                        const wishlistCount = guestWishlist.removeItem(productId);
                        icon.classList.remove('text-red-500');
                        icon.classList.add('text-gray-400');
                        btn.dataset.inWishlist = 'false';
                        updateWishlistCount(-1);
                        return;
                    }

                    // For authenticated users, send to server
                    const response = await fetch(`/wishlist/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    });
                    
                    if (response.ok) {
                        icon.classList.remove('text-red-500');
                        icon.classList.add('text-gray-400');
                        btn.dataset.inWishlist = 'false';
                        updateWishlistCount(-1);
                    }
                } else {
                    // Add to wishlist
                    if (!isAuthenticated) {
                        // For guests, manage wishlist locally
                        const wishlistCount = guestWishlist.addItem(productId);
                        icon.classList.remove('text-gray-400');
                        icon.classList.add('text-red-500');
                        btn.dataset.inWishlist = 'true';
                        updateWishlistCount(1);
                        return;
                    }

                    // For authenticated users, send to server
                    const response = await fetch(`/wishlist/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    });
                    
                    if (response.ok) {
                        icon.classList.remove('text-gray-400');
                        icon.classList.add('text-red-500');
                        btn.dataset.inWishlist = 'true';
                        updateWishlistCount(1);
                    }
                }
            } catch (error) {
                console.error('Wishlist error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Update cart count in header
    function updateCartCount(count) {
        console.log('Updating cart count to:', count);

        // Try multiple selectors to find cart count elements
        const selectors = ['.cart-count', '[class*="cart-count"]', 'span.cart-count'];
        let foundElements = [];

        selectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            if (elements.length > 0) {
                console.log(`Found ${elements.length} elements with selector "${selector}"`);
                foundElements = foundElements.concat(Array.from(elements));
            }
        });

        // Also look for any span elements that might contain cart counts
        const allSpans = document.querySelectorAll('span');
        allSpans.forEach(span => {
            if (span.textContent && /^\d+$/.test(span.textContent.trim()) && span.classList.contains('absolute')) {
                console.log('Found potential cart count span:', span);
                foundElements.push(span);
            }
        });

        console.log('Total elements to update:', foundElements.length);

        if (foundElements.length > 0) {
            // Update existing elements
            foundElements.forEach((element, index) => {
                console.log(`Updating element ${index}:`, element, 'from:', element.textContent, 'to:', count);
                element.textContent = count;
            });
        } else {
            // No cart count element found, create one if count > 0
            if (count > 0) {
                const cartLinks = document.querySelectorAll('a[href*="cart"]');
                cartLinks.forEach(link => {
                    // Check if it already has a cart count span
                    const existingSpan = link.querySelector('.cart-count');
                    if (!existingSpan) {
                        const span = document.createElement('span');
                        span.className = 'cart-count absolute -top-1 -right-1 rounded-full bg-[#5b1e7e] px-1.5 py-0.5 text-xs font-semibold text-white min-w-[18px] text-center';
                        span.textContent = count;
                        link.appendChild(span);
                        console.log('Created new cart count span:', span);
                    }
                });
            }
        }

        // If count is 0, remove the cart count span
        if (count === 0) {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.remove();
                console.log('Removed cart count span');
            });
        }
    }

    // Update wishlist count in header
    function updateWishlistCount(deltaOrCount, isAbsolute = false) {
        const wishlistLinks = document.querySelectorAll('a[href*="wishlist"]');
        let wishlistCountElement = document.querySelector('.wishlist-count');
        
        // Create the badge if it doesn't exist and count > 0
        if (!wishlistCountElement && wishlistLinks.length > 0) {
            const count = isAbsolute ? deltaOrCount : 0;
            if (count > 0) {
                const link = wishlistLinks[0];
                const span = document.createElement('span');
                span.className = 'wishlist-count absolute -top-1 -right-1 rounded-full bg-[#e91e8c] px-1.5 py-0.5 text-xs font-semibold text-white min-w-[18px] text-center';
                span.textContent = count;
                link.appendChild(span);
                wishlistCountElement = span;
            }
        }
        
        if (wishlistCountElement) {
            if (isAbsolute) {
                wishlistCountElement.textContent = deltaOrCount;
            } else {
                const currentCount = parseInt(wishlistCountElement.textContent) || 0;
                const newCount = currentCount + deltaOrCount;
                wishlistCountElement.textContent = newCount;
                
                // Remove badge if count becomes 0
                if (newCount <= 0) {
                    wishlistCountElement.remove();
                }
            }
        }
    }

    // Initialize guest cart count on page load
    if (!isAuthenticated) {
        const guestCartCount = guestCart.getTotalQuantity();
        console.log('Guest user - Initial cart count from localStorage:', guestCartCount);
        updateCartCount(guestCartCount);
    } else {
        // For authenticated users, the cart count should already be set by the server
        // But let's make sure it's properly initialized
        const currentCartElements = document.querySelectorAll('.cart-count');
        if (currentCartElements.length > 0) {
            const currentCount = parseInt(currentCartElements[0].textContent) || 0;
            console.log('Authenticated user - Initial cart count from DOM:', currentCount);
        }
    }

    // Initialize wishlist count on page load
    if (!isAuthenticated) {
        const guestWishlistCount = guestWishlist.getTotalCount();
        console.log('Guest user - Initial wishlist count from localStorage:', guestWishlistCount);
        updateWishlistCount(guestWishlistCount, true); // Set absolute count
    }
});
