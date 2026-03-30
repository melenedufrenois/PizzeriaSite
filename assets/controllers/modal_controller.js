import { Controller } from '@hotwired/stimulus';

/**
 * Accessible modal controller (WCAG 2.1 AA)
 *
 * Usage:
 *   <div data-controller="modal">
 *     <button data-action="modal#open">Ouvrir</button>
 *     <div data-modal-target="dialog" role="dialog" aria-modal="true"
 *          aria-labelledby="modal-title" hidden>
 *       <h2 id="modal-title">Titre de la fenêtre</h2>
 *       <button data-action="modal#close">Fermer</button>
 *       <!-- other content -->
 *     </div>
 *   </div>
 *
 * Features:
 *   - Focus is trapped inside the dialog when open
 *   - ESC key closes the dialog
 *   - Focus returns to the trigger element on close
 */
export default class extends Controller {
    static targets = ['dialog'];

    /** Focusable element selectors (ARIA-visible, enabled) */
    static FOCUSABLE = [
        'a[href]',
        'button:not([disabled])',
        'input:not([disabled])',
        'select:not([disabled])',
        'textarea:not([disabled])',
        '[tabindex]:not([tabindex="-1"])',
    ].join(', ');

    connect() {
        this._onKeyDown = this._handleKeyDown.bind(this);
    }

    disconnect() {
        this._removeTrap();
    }

    /** Open the modal dialog */
    open(event) {
        this._trigger = event.currentTarget;

        const dialog = this.dialogTarget;
        dialog.hidden = false;

        // Announce to screen readers
        dialog.setAttribute('aria-hidden', 'false');

        this._installTrap();

        // Move focus to the first focusable element inside the dialog
        const first = this._focusableElements()[0];
        if (first) {
            first.focus();
        } else {
            dialog.focus();
        }
    }

    /** Close the modal dialog */
    close() {
        const dialog = this.dialogTarget;
        dialog.hidden = true;
        dialog.setAttribute('aria-hidden', 'true');

        this._removeTrap();

        // Return focus to the element that triggered opening
        if (this._trigger) {
            this._trigger.focus();
            this._trigger = null;
        }
    }

    // ------------------------------------------------------------------
    // Private helpers
    // ------------------------------------------------------------------

    _installTrap() {
        document.addEventListener('keydown', this._onKeyDown);
    }

    _removeTrap() {
        document.removeEventListener('keydown', this._onKeyDown);
    }

    _handleKeyDown(event) {
        if (event.key === 'Escape') {
            this.close();
            return;
        }

        if (event.key === 'Tab') {
            this._trapTab(event);
        }
    }

    _trapTab(event) {
        const focusable = this._focusableElements();
        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey) {
            // Shift+Tab: wrap backwards
            if (document.activeElement === first) {
                event.preventDefault();
                last.focus();
            }
        } else {
            // Tab: wrap forwards
            if (document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    }

    _focusableElements() {
        return Array.from(
            this.dialogTarget.querySelectorAll(this.constructor.FOCUSABLE)
        ).filter(el => !el.closest('[hidden]') && el.offsetParent !== null);
    }
}
