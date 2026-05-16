@props(['product'])

<div class="product-gallery group" x-data="productGallery()" @keydown.left="prevImage()" @keydown.right="nextImage()" @keydown.escape="closeModal()" tabindex="0">
    <!-- Main Image Display -->
    <div class="relative overflow-hidden rounded-[2rem] bg-[#f7f2ee] shadow-[0_26px_70px_-45px_rgba(0,0,0,0.2)] cursor-pointer"
        @touchstart="touchStart = $event.touches[0].clientX"
        @touchend="handleSwipe($event)"
        @click="openModal()">
        
        <!-- Image Container -->
        <div class="relative aspect-[4/3] overflow-hidden">
            <template x-for="(image, index) in images" :key="index">
                <img
                    :src="image"
                    :alt="'Product image ' + (index + 1)"
                    class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500"
                    :class="index === currentIndex ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                    x-show="index === currentIndex"
                    loading="lazy"
                />
            </template>

            <!-- Loading Skeleton (optional) -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#f7f2ee] via-[#e8ddd5] to-[#f7f2ee] animate-pulse"
                x-show="!imageLoaded"
            ></div>
        </div>

        <!-- Image Counter -->
        <div class="absolute bottom-4 right-4 z-10 rounded-full bg-black/70 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>

        <!-- Progress Indicator -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-black/10">
            <div class="h-full bg-[#1b1b18] transition-all duration-300"
                :style="{ width: ((currentIndex + 1) / images.length * 100) + '%' }">
            </div>
        </div>

        
    </div>

    <!-- Thumbnails -->
    <div class="flex gap-3 overflow-x-auto overflow-y-hidden pb-2 snap-x snap-mandatory">
        <template x-for="(image, index) in images" :key="index">
            <button
                @click="currentIndex = index"
                class="group relative flex-shrink-0 overflow-hidden rounded-lg transition-all duration-300 snap-start"
                :class="currentIndex === index
                    ? 'ring-2 ring-[#1b1b18] ring-offset-2'
                    : 'hover:ring-1 hover:ring-[#9c8e85]'"
                :aria-label="'View image ' + (index + 1)"
                :aria-current="index === currentIndex"
            >
                <img
                    :src="image"
                    :alt="'Thumbnail ' + (index + 1)"
                    class="h-24 w-24 object-cover transition-transform duration-300 group-hover:scale-110"
                />

                <!-- Active Indicator Overlay -->
                <div
                    v-show="currentIndex === index"
                    class="absolute inset-0 bg-black/0 transition-colors duration-300 ring-inset"
                    :class="currentIndex === index ? 'ring-2 ring-white/50' : ''"
                ></div>
            </button>
        </template>
    </div>


    <!-- Image Popup Modal -->
    <div class="image-modal-backdrop" 
        x-show="showModal" 
        @click="closeModal()"
        @keydown.escape="closeModal()"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;">
        
        <div class="modal-content" @click.stop>
            <!-- Close Button -->
            <button 
                @click="closeModal()" 
                class="modal-close-btn"
                aria-label="Close image modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Full Image -->
            <img 
                :src="images[currentIndex]" 
                :alt="'Full size product image ' + (currentIndex + 1)"
                class="modal-image"
            />

            <!-- Image Counter in Modal -->
            <div class="modal-counter">
                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
            </div>

            <!-- Navigation Arrows (Optional) -->
            <button 
                @click.stop="prevImage()" 
                class="modal-nav-btn modal-nav-prev"
                aria-label="Previous image"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <button 
                @click.stop="nextImage()" 
                class="modal-nav-btn modal-nav-next"
                aria-label="Next image"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div>


    <!-- Alpine.js Component Logic -->
    <script>
        function productGallery() {
            return {
                images: @json($product->images ?? []),
                currentIndex: 0,
                isMobile: window.innerWidth < 768,
                touchStart: 0,
                imageLoaded: true,
                showModal: false,

                nextImage() {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    this.scrollThumbnailIntoView();
                },

                prevImage() {
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                    this.scrollThumbnailIntoView();
                },

                handleSwipe(event) {
                    const touchEnd = event.changedTouches[0].clientX;
                    const diff = this.touchStart - touchEnd;

                    if (Math.abs(diff) > 50) {
                        if (diff > 0) {
                            this.nextImage();
                        } else {
                            this.prevImage();
                        }
                    }
                },

                scrollThumbnailIntoView() {
                    this.$nextTick(() => {
                        const activeButton = this.$el.querySelector('button[aria-current="true"]');
                        if (activeButton) {
                            activeButton.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                        }
                    });
                },

                init() {
                    this.isMobile = window.innerWidth < 768;

                    // Listen for screen resize
                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 768;
                    });

                    // Set focus to element for keyboard navigation
                    this.$el.focus();

                    // Scroll thumbnail into view on mount
                    this.scrollThumbnailIntoView();

                    // Preload next image
                    this.preloadImages();
                },

                preloadImages() {
                    const nextIndex = (this.currentIndex + 1) % this.images.length;
                    const img = new Image();
                    img.src = this.images[nextIndex];
                },

                openModal() {
                    this.showModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.showModal = false;
                    document.body.style.overflow = '';
                }
            }
        }
    </script>
</div>

<style scoped>
    /* Smooth fade animation for images */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .product-gallery img[x-show] {
        animation: fadeIn 0.4s ease-in-out;
    }

    /* Thumbnail hover effect */
    .product-gallery button:hover img {
        filter: brightness(1.1);
    }

    /* Hide scrollbar while keeping functionality */
    .product-gallery > div:nth-child(2) {
        scrollbar-width: thin;
        scrollbar-color: rgba(27, 27, 24, 0.1) transparent;
    }

    .product-gallery > div:nth-child(2)::-webkit-scrollbar {
        height: 4px;
    }

    .product-gallery > div:nth-child(2)::-webkit-scrollbar-track {
        background: transparent;
    }

    .product-gallery > div:nth-child(2)::-webkit-scrollbar-thumb {
        background: rgba(27, 27, 24, 0.1);
        border-radius: 2px;
    }

    .product-gallery > div:nth-child(2)::-webkit-scrollbar-thumb:hover {
        background: rgba(27, 27, 24, 0.2);
    }

    /* Focus styles for accessibility */
    .product-gallery:focus {
        outline: none;
    }

    .product-gallery button:focus-visible {
        outline: 2px solid #1b1b18;
        outline-offset: 2px;
    }

    /* Modal Styles */
    .image-modal-backdrop {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        position: relative;
        width: 90%;
        height: 90vh;
        max-width: 1200px;
        max-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-image {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 0.5rem;
    }

    .modal-close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        background-color: rgba(27, 27, 24, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10000;
        backdrop-filter: blur(4px);
    }

    .modal-close-btn:hover {
        background-color: rgba(27, 27, 24, 0.95);
        transform: scale(1.1);
    }

    .modal-close-btn:focus-visible {
        outline: 2px solid white;
        outline-offset: 2px;
    }

    .modal-counter {
        position: absolute;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(27, 27, 24, 0.8);
        color: white;
        padding: 8px 16px;
        border-radius: 24px;
        font-size: 0.875rem;
        font-weight: 500;
        backdrop-filter: blur(4px);
    }

    .modal-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(27, 27, 24, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10001;
        backdrop-filter: blur(4px);
    }

    .modal-nav-prev {
        left: 16px;
    }

    .modal-nav-next {
        right: 16px;
    }

    .modal-nav-btn:hover {
        background-color: rgba(27, 27, 24, 0.95);
        transform: translateY(-50%) scale(1.1);
    }

    .modal-nav-btn:focus-visible {
        outline: 2px solid white;
        outline-offset: 2px;
    }

    /* Responsive styles for modal */
    @media (max-width: 640px) {
        .modal-content {
            width: 95%;
            height: auto;
            max-height: 85vh;
        }

        .modal-close-btn {
            width: 40px;
            height: 40px;
            top: 12px;
            right: 12px;
        }

        .modal-close-btn svg {
            width: 24px;
            height: 24px;
        }

        .modal-nav-btn {
            width: 40px;
            height: 40px;
        }

        .modal-nav-prev {
            left: 8px;
        }

        .modal-nav-next {
            right: 8px;
        }

        .modal-counter {
            bottom: 12px;
            font-size: 0.75rem;
            padding: 6px 12px;
        }
    }
</style>

