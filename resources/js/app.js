import './bootstrap';
import Alpine from 'alpinejs';
import jspreadsheet from 'jspreadsheet-ce';
import 'jspreadsheet-ce/dist/jspreadsheet.css';

import jSuites from 'jsuites';
import 'jsuites/dist/jsuites.css';

window.Alpine = Alpine;

// Initialize Dark Mode store with better persistence
Alpine.store('darkMode', {
    on: localStorage.getItem('theme') === 'dark',
    toggle() {
        this.on = !this.on;
        localStorage.setItem('theme', this.on ? 'dark' : 'light');
        this.updateDOM();
    },
    updateDOM() {
        if (this.on) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    init() {
        // Check system preference only if no theme is stored
        if (!localStorage.getItem('theme')) {
            this.on = window.matchMedia('(prefers-color-scheme: dark)').matches;
            localStorage.setItem('theme', this.on ? 'dark' : 'light');
        }
        this.updateDOM();
    }
});

// Initialize sidebar store
document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        isOpen: window.innerWidth >= 1024, // Default open on desktop, closed on mobile
        toggle() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('sidebar', this.isOpen);
        },
        close() {
            this.isOpen = false;
            localStorage.setItem('sidebar', false);
        }
    });
});

// Handle window resize
window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        Alpine.store('sidebar').isOpen = true;
    }
});

// Start Alpine
Alpine.start();

// Initialize dark mode on page load
Alpine.store('darkMode').init();