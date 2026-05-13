<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('patients.index') }}" class="text-slate-400 hover:text-blue-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <span class="text-sm font-bold text-blue-600 uppercase tracking-widest">Patient Profile</span>
            </div>
            <h1 class="text-4xl font-bold text-slate-800 tracking-tight">{{ $patient->name }}</h1>
        </div>
        <div class="flex gap-3">
            @unless(auth()->user()->isNurse())
            <a href="{{ route('patients.edit', $patient) }}" class="btn-secondary">Edit Details</a>
            @endunless
            <button onclick="var m=document.getElementById('uploadModal');m.classList.remove('hidden');m.classList.add('flex');" class="btn-primary">Add Medical Report</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Patient Info -->
        <div class="space-y-6">
            <x-card>
                <h3 class="text-lg font-bold text-slate-800 mb-6">Patient Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Current Status</p>
                        <span class="inline-block mt-1 px-3 py-1 rounded-lg text-sm font-bold uppercase
                            @if($patient->status === 'stable') bg-emerald-100 text-emerald-600 
                            @elseif($patient->status === 'critical') bg-rose-100 text-rose-600
                            @else bg-amber-100 text-amber-600 @endif">
                            {{ $patient->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Location</p>
                        <p class="text-slate-700 font-medium">{{ $patient->icu_ward }} - Bed {{ $patient->bed_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Attending physician</p>
                        <p class="text-slate-700 font-medium">{{ $patient->doctor->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Assigned Nurse</p>
                        <p class="text-slate-700 font-medium">{{ $patient->nurse->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Primary family contact</p>
                        <p class="text-slate-700 font-medium">
                            @if($patient->familyUser)
                                {{ $patient->familyUser->name }}<span class="text-slate-400 text-sm font-normal"> — {{ $patient->familyUser->email }}</span>
                            @else
                                Not linked
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Emergency Contact</p>
                        <p class="text-slate-700 font-medium">{{ $patient->emergency_contact ?? 'N/A' }}</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h3 class="text-lg font-bold text-slate-800 mb-6">Medical Reports</h3>
                @if($patient->reports->isEmpty())
                    <p class="text-sm text-slate-500 italic">No reports uploaded yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($patient->reports as $report)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition-all group">
                            <svg class="w-8 h-8 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $report->title }}</p>
                                <p class="text-xs text-slate-500">{{ $report->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('reports.download', $report) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Delete this report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-600 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>

        <!-- Main: Timeline & Appointments -->
        <div class="lg:col-span-2 space-y-8">
            <x-card>
                <h3 class="text-lg font-bold text-slate-800 mb-6">Visit History</h3>
                @if($patient->appointments->isEmpty())
                    <p class="text-slate-500 py-4 text-center">No visits recorded for this patient.</p>
                @else
                    <div class="relative pl-8 space-y-8 before:content-[''] before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                        @foreach($patient->appointments as $visit)
                        <div class="relative">
                            <div class="absolute -left-8 top-1.5 w-6 h-6 rounded-full bg-white border-4 {{ $visit->status === 'approved' ? 'border-blue-500' : 'border-slate-300' }}"></div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-slate-800">Visit with {{ $visit->user->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $visit->scheduled_at->format('M d, Y \a\t H:i') }}</p>
                                    @if($visit->notes)
                                        <p class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 italic">"{{ $visit->notes }}"</p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                    @if($visit->status === 'approved') bg-emerald-100 text-emerald-600 
                                    @elseif($visit->status === 'pending') bg-amber-100 text-amber-600
                                    @else bg-slate-100 text-slate-600 @endif">
                                    {{ $visit->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="glass-card w-full max-w-md p-8 bg-white overflow-hidden relative">
            <button onclick="var m=document.getElementById('uploadModal');m.classList.add('hidden');m.classList.remove('flex');" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-2xl font-bold text-slate-800 mb-6">Upload Medical Report</h3>
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Report Title</label>
                    <input type="text" name="title" class="w-full input-glass" placeholder="e.g. Blood Test Results" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Select File (PDF/Image)</label>
                    <input type="file" name="report_file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full btn-primary py-3">Upload Report</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
