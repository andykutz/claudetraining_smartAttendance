<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Settings</h2>
            <a href="{{ route('admin.settings.api-docs.pdf') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-white bg-white/10 border border-white/20 px-4 py-2 rounded-lg hover:bg-white/20 transition-colors duration-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m-9 8h12a2 2 0 002-2V8a2 2 0 00-2-2h-4l-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Download API Docs (PDF)
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 sm:p-6">
                <h3 class="text-sm font-semibold text-slate-800 mb-2">API &amp; Integration Connection Points</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    This app doesn't expose a token-based JSON API &mdash; every connection point below is a
                    session/cookie-authenticated web route. The reference below is meant for anyone wiring up QR
                    provisioning tools, kiosk devices, or attendance export automation against this app.
                    Use the <span class="font-medium text-blue-700">Download API Docs (PDF)</span> button above to
                    save an offline copy.
                </p>
            </div>

            @foreach ($groups as $group)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 sm:p-6">
                    <h3 class="text-sm font-semibold text-slate-800">{{ $group['name'] }}</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-4">{{ $group['note'] }}</p>

                    <div class="space-y-3">
                        @foreach ($group['endpoints'] as $endpoint)
                            @php
                                $methodColors = [
                                    'GET' => 'bg-blue-100 text-blue-800',
                                    'POST' => 'bg-emerald-100 text-emerald-800',
                                    'PUT' => 'bg-amber-100 text-amber-800',
                                    'DELETE' => 'bg-rose-100 text-rose-800',
                                ];
                            @endphp
                            <div class="border border-slate-200 rounded-lg p-3.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $methodColors[$endpoint['method']] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $endpoint['method'] }}
                                    </span>
                                    <span class="font-mono text-sm text-slate-700">{{ $endpoint['path'] }}</span>
                                    @if (! empty($endpoint['auth']))
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-navy-900 text-white">Session auth required</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-700 mt-2">{{ $endpoint['description'] }}</p>
                                <p class="text-xs text-slate-500 mt-1.5"><span class="font-medium text-slate-600">Params:</span> {{ $endpoint['params'] }}</p>
                                <p class="text-xs text-slate-500 mt-0.5"><span class="font-medium text-slate-600">Response:</span> {{ $endpoint['response'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
