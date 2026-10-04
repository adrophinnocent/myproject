<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- My Safari Proposals --}}
            @if(isset($proposals) && $proposals->count() > 0)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-slate-900 mb-4 font-serif">My Safari Proposals</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-black uppercase text-slate-500">Reference</th>
                                <th class="px-4 py-3 text-left font-black uppercase text-slate-500">Trip Title</th>
                                <th class="px-4 py-3 text-left font-black uppercase text-slate-500">Travel Dates</th>
                                <th class="px-4 py-3 text-left font-black uppercase text-slate-500">Total Price</th>
                                <th class="px-4 py-3 text-left font-black uppercase text-slate-500">Status</th>
                                <th class="px-4 py-3 text-right font-black uppercase text-slate-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @foreach($proposals as $prop)
                            <tr>
                                <td class="px-4 py-3 font-bold text-amber-600">{{ $prop->full_reference }}</td>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $prop->title }}</td>
                                <td class="px-4 py-3 text-slate-600">
                                    @if($prop->start_date)
                                        {{ $prop->start_date->format('d M Y') }}
                                        @if($prop->end_date) - {{ $prop->end_date->format('d M Y') }} @endif
                                    @else
                                        Flexible
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $prop->formatted_total_price }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $prop->status_badge_class }}">
                                        {{ str_replace('_', ' ', $prop->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ $prop->public_url }}" target="_blank" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition-all">
                                        View Proposal
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
