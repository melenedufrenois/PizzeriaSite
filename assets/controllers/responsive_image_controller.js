import { Controller } from '@hotwired/stimulus';

/**
 * Responsive image controller.
 *
 * Handles two concerns:
 *  1. LQIP blur-up: the <img> starts blurred (via inline style set by the
 *     Twig extension) and transitions to sharp once the full image has loaded.
 *  2. Data-saver toggle: a button with data-action="responsive-image#toggleSaveData"
 *     can override the Save-Data preference client-side and reload the page.
 */
export default class extends Controller {
    static values = {
        placeholder: String,
    };

    connect() {
        // Guard: blur-up logic only applies to <img> elements.
        if (!(this.element instanceof HTMLImageElement)) {
            return;
        }

        if (this.element.complete && this.element.naturalWidth > 0) {
            this.#reveal();
        } else {
            this.element.addEventListener('load', () => this.#reveal(), { once: true });
            this.element.addEventListener('error', () => this.#reveal(), { once: true });
        }
    }

    /**
     * Toggle a client-side "data-saver" cookie and reload.
     * Intended for use on a button: data-action="responsive-image#toggleSaveData"
     */
    toggleSaveData(event) {
        event.preventDefault();
        const current = document.cookie.match(/(?:^|;\s*)save-data=([^;]+)/)?.[1];
        const allowedValues = ['on', 'off'];
        const next = current === 'on' ? 'off' : 'on';
        if (!allowedValues.includes(next)) {
            return;
        }
        document.cookie = `save-data=${next}; path=/; max-age=31536000; SameSite=Lax`;
        location.reload();
    }

    // -------------------------------------------------------------------------

    #reveal() {
        this.element.style.filter = 'none';
    }
}
