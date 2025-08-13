import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Initialize Dark Mode store
Alpine.store('darkMode', {
    on: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggle() {
        this.on = !this.on;
        localStorage.theme = this.on ? 'dark' : 'light';
        this.updateDOM();
    },
    updateDOM() {
        if (this.on) {
            document.documentElement.classList.add('dark');
            document.body.style.backgroundColor = '#595758'; // Set custom dark background
        } else {
            document.documentElement.classList.remove('dark');
            document.body.style.backgroundColor = ''; // Reset to default
        }
    },
    init() {
        this.updateDOM();
    }
});

// Initialize Sidebar store
Alpine.store('sidebar', {
    isOpen: window.innerWidth >= 1024, // Default open on desktop, closed on mobile
    toggle() {
        this.isOpen = !this.isOpen;
        localStorage.setItem('sidebar', this.isOpen);
    }
});

// Start Alpine
Alpine.start();

// Initialize dark mode on page load
Alpine.store('darkMode').init();
