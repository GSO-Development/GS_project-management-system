<style>
    .nexus-confirm-dialog {
        width: min(460px, calc(100vw - 32px)); max-width: none; max-height: calc(100dvh - 32px);
        padding: 0; margin: auto; overflow: auto; overscroll-behavior: contain;
        border: 1px solid #e2e8f0; border-radius: 18px; background: #fff; color: #0f172a;
        box-shadow: 0 24px 80px rgba(15, 23, 42, .24); font-family: 'Inter', system-ui, sans-serif;
    }
    .nexus-confirm-dialog::backdrop { background: rgba(15, 23, 42, .48); backdrop-filter: blur(4px); }
    .nexus-confirm-dialog[open] { animation: nexus-confirm-enter .16s ease-out; }
    .nexus-confirm-content { position: relative; padding: 26px 26px 24px; }
    .nexus-confirm-icon { display: grid; place-items: center; width: 46px; height: 46px; margin-bottom: 18px; border: 1px solid #ffe0e6; border-radius: 13px; background: #fff1f4; color: #c3122e; }
    .nexus-confirm-dialog .nexus-confirm-icon svg { width: 23px; height: 23px; }
    .nexus-confirm-dialog h2 { margin: 0 36px 10px 0; font-size: 20px; font-weight: 750; letter-spacing: -.5px; line-height: 1.35; }
    .nexus-confirm-message { margin: 0; color: #64748b; font-size: 13px; line-height: 1.75; white-space: pre-line; overflow-wrap: anywhere; }
    .nexus-confirm-context { margin: 16px 0 0; padding: 12px 14px; border: 1px solid #e8edf3; border-radius: 10px; background: #f8fafc; color: #334155; font-size: 13px; font-weight: 600; overflow-wrap: anywhere; }
    .nexus-confirm-close { position: absolute; top: 22px; right: 22px; display: grid; place-items: center; width: 32px; height: 32px; padding: 0; border: 0; border-radius: 8px; background: transparent; color: #94a3b8; cursor: pointer; }
    .nexus-confirm-close:hover { background: #f1f5f9; color: #334155; }
    .nexus-confirm-dialog .nexus-confirm-close svg { width: 17px; height: 17px; }
    .nexus-confirm-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 17px 26px; border-top: 1px solid #edf0f4; background: #fafbfc; }
    .nexus-confirm-button { min-height: 40px; padding: 9px 18px; border: 1px solid #dfe5ed; border-radius: 9px; background: #fff; color: #475569; font: 600 13px/1.4 'Inter', system-ui, sans-serif; cursor: pointer; }
    .nexus-confirm-button:hover { background: #f1f5f9; }
    .nexus-confirm-accept { border-color: #c3122e; background: #c3122e; color: #fff; }
    .nexus-confirm-accept:hover { border-color: #a81029; background: #a81029; }
    .nexus-confirm-dialog button:focus-visible, .nexus-confirm-dialog input:focus-visible { outline: 3px solid rgba(195, 18, 46, .25); outline-offset: 3px; }
    .nexus-confirm-dialog button:disabled { opacity: .5; cursor: not-allowed; }
    .nexus-confirm-prompt { margin-top: 16px; color: #475569; font-size: 12px; line-height: 1.6; }
    .nexus-confirm-prompt code { font-weight: 700; color: #0f172a; }
    .nexus-confirm-prompt input { display: block; box-sizing: border-box; width: 100%; margin-top: 8px; padding: 10px 12px; border: 1px solid #dfe5ed; border-radius: 9px; font: inherit; color: #0f172a; background: #fff; }
    .nexus-confirm-dialog [hidden] { display: none !important; }
    @keyframes nexus-confirm-enter { from { opacity: 0; transform: translateY(6px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @media (prefers-reduced-motion: reduce) { .nexus-confirm-dialog[open] { animation: none; } }
    @media (max-width: 480px) {
        .nexus-confirm-content { padding: 22px 20px; }
        .nexus-confirm-footer { padding: 16px 20px; }
        .nexus-confirm-button { flex: 1; }
    }
</style>

<dialog id="confirm-modal" class="nexus-confirm-dialog" role="alertdialog" aria-modal="true" aria-labelledby="nexus-confirm-title" aria-describedby="nexus-confirm-message">
    <div class="nexus-confirm-content">
        <button type="button" class="nexus-confirm-close" data-confirm-cancel aria-label="Close confirmation">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
        <div class="nexus-confirm-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v5m0 3h.01"/><circle cx="12" cy="12" r="9"/></svg>
        </div>
        <h2 id="nexus-confirm-title">Confirm action</h2>
        <p id="nexus-confirm-message" class="nexus-confirm-message"></p>
        <p class="nexus-confirm-context" data-confirm-context hidden></p>
        <label class="nexus-confirm-prompt" data-confirm-prompt hidden>
            Type <code data-confirm-expected></code> to confirm.
            <input type="text" data-confirm-input autocomplete="off" spellcheck="false">
        </label>
    </div>
    <div class="nexus-confirm-footer">
        <button type="button" class="nexus-confirm-button" data-confirm-cancel autofocus>Cancel</button>
        <button type="button" class="nexus-confirm-button nexus-confirm-accept" data-confirm-accept>Confirm</button>
    </div>
</dialog>

<script>
(() => {
    // Keep one handler across Livewire page navigation; find the current dialog on each request.
    if (window.__nexusConfirmInstalled) return;
    window.__nexusConfirmInstalled = true;

    let active = null;
    const boundDialogs = new WeakSet();

    function finish(accepted) {
        if (!active) return;
        const current = active;
        active = null;
        if (current.dialog.open) current.dialog.close();
        document.documentElement.style.overflow = current.overflow;
        if (current.trigger?.isConnected) current.trigger.focus({ preventScroll: true });
        current.resolve(accepted);
    }

    function bindDialog(dialog) {
        if (boundDialogs.has(dialog)) return;
        boundDialogs.add(dialog);
        let backdropPressed = false;
        dialog.addEventListener('pointerdown', event => {
            const bounds = dialog.getBoundingClientRect();
            backdropPressed = event.target === dialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom);
        });
        dialog.addEventListener('click', event => {
            // Do not dismiss the event details or other popup underneath this dialog.
            event.stopPropagation();
            if (event.target.closest('[data-confirm-cancel]')) finish(false);
            else if (event.target.closest('[data-confirm-accept]')) {
                if (!event.target.closest('button').disabled) finish(true);
            } else if (backdropPressed && event.target === dialog) finish(false);
            backdropPressed = false;
        });
        dialog.addEventListener('keydown', event => {
            event.stopPropagation();
            if (event.key !== 'Tab') return;
            const controls = [...dialog.querySelectorAll('button:not(:disabled), input:not(:disabled)')].filter(control => control.getClientRects().length > 0);
            const first = controls[0];
            const last = controls[controls.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });
        dialog.addEventListener('cancel', event => {
            event.preventDefault();
            event.stopPropagation();
            finish(false);
        });
        dialog.addEventListener('close', () => {
            if (active?.dialog === dialog && !dialog.open) finish(false);
        });
        dialog.querySelector('[data-confirm-input]').addEventListener('input', event => {
            if (active?.dialog === dialog) {
                dialog.querySelector('[data-confirm-accept]').disabled = event.target.value !== active.expected;
            }
        });
    }

    window.nexusConfirm = function (title, message, confirmText = 'Confirm', options = {}) {
        const dialog = document.getElementById('confirm-modal');
        // A repeated click must never queue a second destructive action.
        if (!dialog || active) return Promise.resolve(false);
        bindDialog(dialog);
        dialog.querySelector('#nexus-confirm-title').textContent = title;
        dialog.querySelector('#nexus-confirm-message').textContent = message;
        const context = dialog.querySelector('[data-confirm-context]');
        context.textContent = options.context || '';
        context.hidden = !options.context;
        const prompt = dialog.querySelector('[data-confirm-prompt]');
        prompt.hidden = !options.expected;
        dialog.querySelector('[data-confirm-expected]').textContent = options.expected || '';
        dialog.querySelector('[data-confirm-input]').value = '';
        const accept = dialog.querySelector('[data-confirm-accept]');
        accept.textContent = confirmText;
        accept.disabled = Boolean(options.expected);

        return new Promise(resolve => {
            active = { dialog, resolve, expected: options.expected, trigger: options.trigger || document.activeElement, overflow: document.documentElement.style.overflow };
            document.documentElement.style.overflow = 'hidden';
            try {
                dialog.showModal();
            } catch (error) {
                finish(false);
            }
        });
    };

    function installLivewireConfirmation() {
        if (window.__nexusConfirmHookInstalled || !window.Livewire) return;
        window.__nexusConfirmHookInstalled = true;
        Livewire.hook('directive.init', ({ el, directive, cleanup }) => {
            if (directive.value !== 'confirm') return;
            // Livewire calls this continuation before executing wire:click or wire:submit.
            // Replace its native browser confirmation without changing the server action.
            el.__livewire_confirm = (action, instead = () => {}) => {
                let message = (directive.expression || 'Are you sure?').replaceAll('\\n', '\n');
                let expected;
                if (directive.modifiers.includes('prompt')) {
                    [message, expected] = message.split('|');
                    if (!expected) { instead(); return; }
                }
                const verb = message.match(/\b(delete|remove|clear|withdraw|reset)\b/i)?.[1]?.toLowerCase();
                const labels = { delete: ['Delete item?', 'Delete'], remove: ['Remove item?', 'Remove'], clear: ['Clear items?', 'Clear'], withdraw: ['Withdraw request?', 'Withdraw'], reset: ['Reset permissions?', 'Reset'] };
                const [title, button] = labels[verb] || ['Confirm action', 'Confirm'];
                window.nexusConfirm(el.dataset.confirmTitle || title, message, el.dataset.confirmButton || button, {
                    context: el.dataset.confirmContext,
                    expected,
                    trigger: el,
                }).then(accepted => {
                    if (accepted && el.isConnected) action();
                    else instead();
                });
            };
            cleanup(() => {
                if (active?.trigger === el) finish(false);
                delete el.__livewire_confirm;
            });
        });
    }

    document.addEventListener('livewire:init', installLivewireConfirmation);
    installLivewireConfirmation();
    document.addEventListener('livewire:navigating', () => finish(false));
})();
</script>
