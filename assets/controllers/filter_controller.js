import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['panel', 'arrow'];

    connect() {
        // Check if there are active filters on page load
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('base') || urlParams.has('type') || urlParams.has('ingredient')) {
            this.open();
        }
    }

    toggle() {
        if (this.panelTarget.classList.contains('hidden')) {
            this.open();
        } else {
            this.close();
        }
    }

    open() {
        this.panelTarget.classList.remove('hidden');
        this.panelTarget.classList.add('animate-fade-in');
        if (this.hasArrowTarget) {
            this.arrowTarget.classList.add('rotate-180');
        }
    }

    close() {
        this.panelTarget.classList.add('hidden');
        this.panelTarget.classList.remove('animate-fade-in');
        if (this.hasArrowTarget) {
            this.arrowTarget.classList.remove('rotate-180');
        }
    }
}
