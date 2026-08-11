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
//  PREMIUM TOAST NOTIFICATION SYSTEM
// ════════════════════════════════════════════════════════
window.showToast = function (message, type = 'success', duration = 4500) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Config per type
    const config = {
        success: {
            bar:  '#22c55e',
            icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>`,
            iconBg: '#dcfce7',
            iconColor: '#16a34a',
        },
        error: {
            bar:  '#ef4444',
            icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>`,
            iconBg: '#fee2e2',
            iconColor: '#dc2626',
        },
        warning: {
            bar:  '#f59e0b',
            icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>`,
            iconBg: '#fef3c7',
            iconColor: '#d97706',
        },
        info: {
            bar:  '#3b82f6',
            icon: `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>`,
            iconBg: '#dbeafe',
            iconColor: '#2563eb',
        },
    };

    const c = config[type] || config.info;

    // Build toast element
    const toast = document.createElement('div');
    toast.setAttribute('data-toast', '');
    toast.style.cssText = `
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
        min-width: 300px;
        max-width: 380px;
        transform: translateX(110%);
        opacity: 0;
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
        cursor: default;
        pointer-events: all;
    `;

    // Colored left accent bar
    const bar = document.createElement('div');
    bar.style.cssText = `
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: ${c.bar};
        border-radius: 14px 0 0 14px;
    `;

    // Icon wrapper
    const iconWrap = document.createElement('div');
    iconWrap.style.cssText = `
        width: 36px; height: 36px;
        border-radius: 10px;
        background: ${c.iconBg};
        color: ${c.iconColor};
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    `;
    iconWrap.innerHTML = c.icon;

    // Message text
    const text = document.createElement('p');
    text.style.cssText = `
        flex: 1;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.45;
        margin: 0;
        padding-top: 2px;
        font-family: 'Inter', system-ui, sans-serif;
    `;
    text.textContent = message;

    // Close button
    const closeBtn = document.createElement('button');
    closeBtn.style.cssText = `
        flex-shrink: 0;
        width: 24px; height: 24px;
        border: none; background: none;
        border-radius: 6px;
        color: #94a3b8;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.15s, color 0.15s;
        padding: 0;
    `;
    closeBtn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>`;
    closeBtn.addEventListener('mouseenter', () => {
        closeBtn.style.background = '#f1f5f9';
        closeBtn.style.color = '#475569';
    });
    closeBtn.addEventListener('mouseleave', () => {
        closeBtn.style.background = 'none';
        closeBtn.style.color = '#94a3b8';
    });

    // Progress bar (timer indicator)
    const progress = document.createElement('div');
    progress.style.cssText = `
        position: absolute;
        bottom: 0; left: 0;
        height: 3px;
        width: 100%;
        background: ${c.bar};
        opacity: 0.35;
        border-radius: 0 0 14px 14px;
        transform-origin: left;
        transition: transform ${duration}ms linear;
    `;

    toast.appendChild(bar);
    toast.appendChild(iconWrap);
    toast.appendChild(text);
    toast.appendChild(closeBtn);
    toast.appendChild(progress);

    container.appendChild(toast);

    // Remove function
    const removeToast = () => {
        toast.style.transform = 'translateX(110%)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 350);
    };

    closeBtn.addEventListener('click', removeToast);

    // Animate in
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';

            // Start progress shrink
            setTimeout(() => {
                progress.style.transform = 'scaleX(0)';
            }, 50);
        });
    });

    // Auto remove
    const timer = setTimeout(removeToast, duration);

    // Pause on hover
    toast.addEventListener('mouseenter', () => {
        clearTimeout(timer);
        progress.style.transitionDuration = '0ms';
    });

    return toast;
};

// ════════════════════════════════════════════════════════
//  Livewire Event Bridge
// ════════════════════════════════════════════════════════
document.addEventListener('livewire:init', () => {
    Livewire.on('toast', (data) => {
        if (Array.isArray(data)) {
            data.forEach(item => showToast(item.message, item.type));
        } else {
            showToast(data.message, data.type);
        }
    });
});
