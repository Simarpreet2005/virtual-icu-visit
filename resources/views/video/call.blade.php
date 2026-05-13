<x-app-layout>
    <div class="h-[calc(100vh-8rem)] flex flex-col gap-6" data-jitsi-call-page>

        <!-- Header -->
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Virtual ICU Visit</h2>
                <p class="text-slate-500">Patient: {{ $appointment->patient->name }} | {{ $appointment->user->name }} (Family)</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Jitsi Container -->
        <div class="flex-1 w-full bg-slate-900 rounded-2xl overflow-hidden shadow-xl border border-slate-200">
            <div id="jitsi-container" class="w-full h-full min-h-[500px]"></div>
        </div>
    </div>

    <!-- Jitsi Meet External API -->
    <script src="https://meet.jit.si/external_api.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const domain = 'meet.jit.si';
            const options = {
                roomName: 'ICU-Visit-{{ $appointment->room_id }}',
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#jitsi-container'),
                userInfo: {
                    displayName: '{{ addslashes(auth()->user()->name) }} ({{ ucfirst(auth()->user()->role) }})'
                },
                configOverwrite: {
                    startWithAudioMuted: false,
                    startWithVideoMuted: false,
                    prejoinPageEnabled: false, // Skip prejoin for immediate connection
                },
                interfaceConfigOverwrite: {
                    SHOW_JITSI_WATERMARK: false,
                    SHOW_WATERMARK_FOR_GUESTS: false,
                    SHOW_BRAND_WATERMARK: false,
                }
            };

            const api = new JitsiMeetExternalAPI(domain, options);

            // Redirect to dashboard when call is ended
            api.addListener('videoConferenceLeft', () => {
                window.location.href = '{{ route('dashboard') }}';
            });
        });
    </script>
</x-app-layout>
