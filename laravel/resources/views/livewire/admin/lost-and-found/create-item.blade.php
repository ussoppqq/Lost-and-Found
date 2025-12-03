<div x-data="{
    modalOpen: @entangle('showModal')
}"
x-init="
    $watch('modalOpen', value => {
        if (value) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    })
">
    @if($showModal)
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity z-40"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl my-8 max-h-[90vh] overflow-y-auto">
                
                @include('livewire.admin.lost-and-found.partials.create-item-header')

                <!-- Form -->
                <form wire:submit.prevent="save">
                    <div class="px-6 py-6">
                        
                        @if($mode === 'from-report')
                            @include('livewire.admin.lost-and-found.partials.create-item-report-info')
                        @endif

                        @if($mode === 'standalone')
                            @include('livewire.admin.lost-and-found.partials.create-item-type-indicator')
                        @endif

                        @include('livewire.admin.lost-and-found.partials.create-item-form-fields')
                    </div>

                    @include('livewire.admin.lost-and-found.partials.create-item-footer')
                </form>
            </div>
        </div>
    @endif

    <!-- Realtime Clock Script (Fixed Timezone) -->
    <script>
        document.addEventListener('livewire:initialized', function() {
            let clockInterval;
            let datetimeInterval;

            function updateClock() {
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                    timeZone: 'Asia/Jakarta'
                };
                
                const formattedTime = new Date().toLocaleString('en-US', options);
                const clockElement = document.getElementById('currentTime');
                if (clockElement) {
                    clockElement.textContent = 'Current Time (WIB): ' + formattedTime;
                }
            }
            
            function updateDatetimeInput() {
                // Dapatkan waktu WIB yang benar
                const wibDate = new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' });
                const wibTime = new Date(wibDate);
                
                const year = wibTime.getFullYear();
                const month = String(wibTime.getMonth() + 1).padStart(2, '0');
                const day = String(wibTime.getDate()).padStart(2, '0');
                const hours = String(wibTime.getHours()).padStart(2, '0');
                const minutes = String(wibTime.getMinutes()).padStart(2, '0');
                
                const datetimeValue = `${year}-${month}-${day}T${hours}:${minutes}`;
                
                const datetimeInput = document.getElementById('report-datetime');
                if (datetimeInput) {
                    // Selalu set value (override jika ada)
                    datetimeInput.value = datetimeValue;
                    
                    // Trigger input event untuk Livewire
                    datetimeInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
            
            // Update clock every second
            clockInterval = setInterval(updateClock, 1000);
            
            // Update datetime input every minute
            datetimeInterval = setInterval(updateDatetimeInput, 60000);
            
            // Initial updates
            updateClock();
            updateDatetimeInput();
            
            // Listen for modal open events to reset & update
            Livewire.on('open-create-item-modal', function() {
                setTimeout(function() {
                    updateClock();
                    updateDatetimeInput();
                }, 300);
            });
            
            Livewire.on('open-create-item-modal-standalone', function() {
                setTimeout(function() {
                    updateClock();
                    updateDatetimeInput();
                }, 300);
            });
            
            // Cleanup intervals jika page unload
            window.addEventListener('beforeunload', function() {
                clearInterval(clockInterval);
                clearInterval(datetimeInterval);
            });
        });
    </script>
</div>