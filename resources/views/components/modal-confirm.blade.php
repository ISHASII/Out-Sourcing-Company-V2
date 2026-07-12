<div x-data="{ 
        show: false, 
        title: '', 
        message: '', 
        confirmText: '', 
        confirmColor: 'bg-[#003d7c] hover:bg-blue-800',
        actionType: '', // 'submit' atau 'link'
        formElement: null,
        redirectUrl: ''
    }" 
    @open-confirm-modal.window="
        show = true; 
        title = $event.detail.title;
        message = $event.detail.message;
        confirmText = $event.detail.confirmText || 'Ya, Lanjutkan';
        
        let type = $event.detail.type || 'info';
        if(type === 'danger') confirmColor = 'bg-red-600 hover:bg-red-700 shadow-red-500/30';
        else if(type === 'warning') confirmColor = 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/30';
        else confirmColor = 'bg-[#003d7c] hover:bg-blue-800 shadow-blue-900/30';

        actionType = $event.detail.actionType || 'submit';
        if (actionType === 'submit') {
            formElement = $event.detail.formElement;
        } else {
            redirectUrl = $event.detail.redirectUrl;
        }
    "
    x-show="show" 
    class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden" 
    x-cloak
    style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
         @click="show = false"></div>

    <!-- Modal Panel -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full mx-4 p-7 overflow-hidden transform transition-all border border-slate-100">
        
        <div class="text-center">
            <!-- Icon Dynamic based on confirmColor -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full mb-5" :class="confirmColor.includes('red') ? 'bg-red-50 text-red-500' : (confirmColor.includes('amber') ? 'bg-amber-50 text-amber-500' : 'bg-blue-50 text-[#003d7c]')">
                <!-- Warning/Danger Icon -->
                <svg x-show="confirmColor.includes('red') || confirmColor.includes('amber')" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <!-- Info/Question Icon -->
                <svg x-show="!confirmColor.includes('red') && !confirmColor.includes('amber')" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h3 class="text-xl font-black text-slate-800 mb-2" x-text="title"></h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed" x-text="message"></p>
        </div>

        <div class="flex gap-3 w-full">
            <button @click="show = false" type="button" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors">
                Batal
            </button>
            <button @click="
                if(actionType === 'submit') { formElement.submit(); } 
                else { window.location.href = redirectUrl; }
                show = false;
            " type="button" class="flex-1 px-4 py-3 text-white font-bold rounded-xl transition-all shadow-lg" :class="confirmColor" x-text="confirmText">
            </button>
        </div>
    </div>
</div>
