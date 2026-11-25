@extends('admin.layouts.app')

@section('title', 'Room Types Management')
@section('page-title', 'Room Types & Supplements')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, currentId: null, name: '', occupants: 2, defaultSupp: 0, desc: '', isActive: true }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif font-bold text-2xl text-stone-900">Room Types & Preference Management</h2>
            <p class="text-xs text-stone-500 mt-0.5">Configure room categories (Single, Double, Twin, Triple, Family, Custom) and room supplement prices.</p>
        </div>
        <button @click="editMode = false; name = ''; occupants = 2; defaultSupp = 0; desc = ''; isActive = true; showModal = true" class="btn-gold px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-wider shadow-md inline-flex items-center gap-2">
            <span>+ Add Room Type</span>
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Room Types List --}}
    <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-500 uppercase font-black text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Room Type Name</th>
                        <th class="px-6 py-4">Max Occupants</th>
                        <th class="px-6 py-4">Default Supplement Charge</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($roomTypes as $rt)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-stone-900 text-sm block">{{ $rt->name }}</span>
                            <span class="text-stone-400 text-[10px]">{{ $rt->description }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-stone-800">
                            👥 {{ $rt->max_occupants }} Persons
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-amber-800">
                            ${{ number_format($rt->default_supplement_price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full font-bold text-[10px] uppercase {{ $rt->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                {{ $rt->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editMode = true; currentId = {{ $rt->id }}; name = @js($rt->name); occupants = {{ $rt->max_occupants }}; defaultSupp = {{ $rt->default_supplement_price }}; desc = @js($rt->description); isActive = {{ $rt->is_active ? 'true' : 'false' }}; showModal = true"
                                        class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-800 font-bold rounded-lg transition-colors">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.room-types.destroy', $rt) }}" onsubmit="return confirm('Delete room type?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-lg font-bold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-stone-400 italic">No room types configured. Click "+ Add Room Type" to create Single, Double, Family, etc.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Room Type Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full border border-stone-200 shadow-2xl space-y-4 text-xs" @click.away="showModal = false">
            <h3 class="font-serif font-bold text-xl text-stone-900" x-text="editMode ? 'Edit Room Type' : 'Add New Room Type'"></h3>

            <form :action="editMode ? '/admin/room-types/' + currentId : '{{ route('admin.room-types.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Room Type Name *</label>
                    <input type="text" name="name" x-model="name" required placeholder="e.g. Single Room, Family Suite, Triple" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-sm font-semibold outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Max Occupants *</label>
                        <input type="number" name="max_occupants" x-model="occupants" min="1" max="10" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Supplement ($)</label>
                        <input type="number" step="0.01" name="default_supplement_price" x-model="defaultSupp" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none font-bold">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Description</label>
                    <input type="text" name="description" x-model="desc" placeholder="e.g. Private single room supplement charge" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div class="flex items-center pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" :checked="isActive" class="w-4 h-4 text-amber-600 rounded">
                        <span class="font-bold text-stone-800">Active Option</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-stone-100">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-stone-300 text-stone-600 rounded-xl font-bold">Cancel</button>
                    <button type="submit" class="btn-gold px-6 py-2 rounded-xl font-black uppercase tracking-wider" x-text="editMode ? 'Update' : 'Create'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
