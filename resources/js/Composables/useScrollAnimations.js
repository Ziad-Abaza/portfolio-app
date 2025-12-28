import { onMounted, onUnmounted } from 'vue';

export function useScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const handleIntersection = (entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                // Add stagger for multiple elements
                const staggerElements = entry.target.querySelectorAll('[data-stagger]');
                staggerElements.forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('animate-in');
                    }, index * 100);
                });
            }
        });
    };

    const observer = new IntersectionObserver(handleIntersection, observerOptions);

    const observeElements = () => {
        const elements = document.querySelectorAll('.animate-on-scroll');
        elements.forEach(el => {
            observer.observe(el);
        });
    };

    onMounted(() => {
        observeElements();
    });

    onUnmounted(() => {
        observer.disconnect();
    });

    return {
        observeElements
    };
}
