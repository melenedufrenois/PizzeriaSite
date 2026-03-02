import { Controller } from '@hotwired/stimulus';

/**
 * Provides haptic feedback and skeleton loading on Turbo navigations.
 *
 * Usage: attach to <body> or a wrapper element via
 *   data-controller="navigation-feedback"
 *
 * The controller:
 *  - Triggers haptic feedback once per navigation (vibrate API)
 *  - Shows a skeleton loader overlay during page transitions
 *  - Hides the skeleton when the new page is fully rendered
 */
export default class extends Controller {
    static targets = ['content', 'skeleton'];

    connect() {
        this._onBeforeVisit = this._onBeforeVisit.bind(this);
        this._onBeforeRender = this._onBeforeRender.bind(this);
        this._onLoad = this._onLoad.bind(this);

        document.addEventListener('turbo:before-visit', this._onBeforeVisit);
        document.addEventListener('turbo:before-render', this._onBeforeRender);
        document.addEventListener('turbo:load', this._onLoad);
    }

    disconnect() {
        document.removeEventListener('turbo:before-visit', this._onBeforeVisit);
        document.removeEventListener('turbo:before-render', this._onBeforeRender);
        document.removeEventListener('turbo:load', this._onLoad);
    }

    /**
     * Fired when Turbo starts a visit (link click).
     * Triggers haptic feedback once and shows the skeleton.
     */
    _onBeforeVisit() {
        // Haptic feedback — single short vibration (only on compatible devices)
        if (navigator.vibrate) {
            navigator.vibrate(15);
        }

        this._showSkeleton();
    }

    /**
     * Fired just before Turbo swaps the new body in.
     * Keep the skeleton visible during the swap.
     */
    _onBeforeRender() {
        // Skeleton is already visible, nothing extra needed
    }

    /**
     * Fired when the new page is fully loaded and rendered.
     * Hides the skeleton.
     */
    _onLoad() {
        this._hideSkeleton();
    }

    _showSkeleton() {
        if (this.hasSkeletonTarget) {
            this.skeletonTarget.classList.remove('hidden');
        }
        if (this.hasContentTarget) {
            this.contentTarget.classList.add('opacity-30', 'pointer-events-none');
            this.contentTarget.style.transition = 'opacity 150ms ease';
        }
    }

    _hideSkeleton() {
        if (this.hasSkeletonTarget) {
            this.skeletonTarget.classList.add('hidden');
        }
        if (this.hasContentTarget) {
            this.contentTarget.classList.remove('opacity-30', 'pointer-events-none');
        }
    }

    /**
     * Called when a filter/category pill is clicked directly (for non-Turbo fallback).
     * Can be wired via data-action="click->navigation-feedback#onFilterClick"
     */
    onFilterClick() {
        if (navigator.vibrate) {
            navigator.vibrate(15);
        }
        this._showSkeleton();
    }
}
