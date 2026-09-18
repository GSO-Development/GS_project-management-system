import './bootstrap';
import Chart from 'chart.js/auto';
import Sortable from 'sortablejs';

// Make Chart.js globally accessible for Blade/Livewire components
window.Chart = Chart;

// Make SortableJS globally accessible
window.Sortable = Sortable;

// ════════════════════════════════════════════════════════
//  EXACT WORD (.DOCX) DOCUMENT VISUAL PREVIEW RENDERER
// ════════════════════════════════════════════════════════
window.renderDocxPreview = function(fileUrl, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = `
        <div class="flex items-center justify-center gap-2.5 py-12 text-slate-500 text-xs font-medium">
            <svg class="animate-spin w-5 h-5 text-[#c3122e]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            <span>Rendering document visual layout & formatting...</span>
        </div>
    `;

    if (typeof docx === 'undefined') {
        setTimeout(() => window.renderDocxPreview(fileUrl, containerId), 400);
        return;
    }

    fetch(fileUrl)
        .then(res => {
            if (!res.ok) throw new Error('File fetch failed');
            return res.arrayBuffer();
        })
        .then(buffer => {
            container.innerHTML = '';
            docx.renderAsync(buffer, container, null, {
                className: 'docx-preview-wrapper',
                inWrapper: true,
                ignoreWidth: false,
                ignoreHeight: false,
                ignoreFonts: false,
                breakPages: true,
                ignoreLastRenderedPageBreak: false,
                experimental: true,
            });
        })
        .catch(err => {
            console.error('Docx render error:', err);
            container.innerHTML = `
                <div class="p-6 text-center text-rose-600 text-xs font-semibold bg-rose-50 rounded-xl border border-rose-200">
                    Failed to render visual document layout.
                </div>
            `;
        });
};

// ════════════════════════════════════════════════════════
//  PREMIUM TOAST NOTIFICATION DELEGATE
// ════════════════════════════════════════════════════════
if (typeof window.showToast !== 'function') {
    window.showToast = function (message, type = 'success', duration = 4500) {
        if (typeof window.__gsShowToastMaster === 'function') {
            window.__gsShowToastMaster(message, type, duration);
        }
    };
    window.toast = window.showToast;
}

