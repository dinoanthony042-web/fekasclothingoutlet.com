@props(['product'])

<div class="product-gallery group" x-data="productGallery()" @keydown.left="prevImage()" @keydown.right="nextImage()" tabindex="0">
    <!-- Main Image Display -->
    <div class="relative overflow-hidden rounded-[2rem] bg-[#f7f2ee] shadow-[0_26px_70px_-45px_rgba(0,0,0,0.2)]"
        @touchstart="touchStart = $event.touches[0].clientX"
        @touchend="handleSwipe($event)">
        
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

        <!-- Mobile Swipe Indicator -->
        <div x-show="isMobile && images.length > 1" class="absolute top-4 left-1/2 -translate-x-1/2 z-10 rounded-full bg-black/70 px-3 py-1 text-xs text-white backdrop-blur-sm">
            👆 Swipe to explore
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

    <!-- Keyboard Navigation Hint -->
    <p class="mt-3 text-center text-xs text-[#8b7f7a] animate-fade-in">
        ⌨️ Use ← → arrows to navigate
    </p>

    <!-- Alpine.js Component Logic -->
    <script>
        function productGallery() {
            return {
                images: @json($product->images ?? []),
                currentIndex: 0,
                isMobile: window.innerWidth < 768,
                touchStart: 0,
                imageLoaded: true,

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
</style>

