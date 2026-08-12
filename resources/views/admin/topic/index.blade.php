@extends('admin.layout')

@section('title', 'Topic Management')
@section('header_title', 'Topic Management')

@section('content')
<div class="py-1 w-full mx-auto">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-end gap-3 mb-6">
        <button onclick="openModal('addTopicModal', 'modalContainer')" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-normal text-sm transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Topic
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border-x border-slate-200 dark:border-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr class="border-y border-slate-200 dark:border-slate-700 divide-x divide-slate-200 dark:divide-slate-700">
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100 w-16 text-center">SL</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100">Topic Name</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100">Slug</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-900 dark:text-slate-100 text-right w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($topics as $topic)
                    <tr class="border-b border-slate-200 dark:border-slate-700 divide-x divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3 px-4 text-center">
                            <span class="text-xs font-normal text-black dark:text-white">
                                {{ $topics->firstItem() + $loop->index }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                                <span class="text-sm font-normal text-black dark:text-white">{{ $topic->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $topic->slug }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if(!$topic->can_delete)
                                    <span class="px-2 py-0.5 text-[10px] font-medium bg-slate-100 text-slate-500 rounded uppercase tracking-wider mr-2">Permanent</span>
                                @endif
                                <button
                                    type="button"
                                    class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors topic-edit-btn"
                                    title="Edit"
                                    data-id="{{ $topic->id }}"
                                    data-name="{{ $topic->name }}"
                                    data-slug="{{ $topic->slug }}"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                @if($topic->can_delete)
                                <form action="{{ route('admin.topics.destroy', $topic->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this topic?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-slate-400 dark:text-slate-500 text-sm">No topics found. Add your first topic.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($topics->hasPages())
        <div class="pt-6 border-t border-slate-100 dark:border-slate-800 mt-4 px-4 pb-4">
            {{ $topics->links() }}
        </div>
        @endif
    </div>
</div>

<x-admin.modal-scripts />

{{-- ADD TOPIC MODAL --}}
<div id="addTopicModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('addTopicModal', 'modalContainer')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4" onclick="modalBackdropClose(event, 'addTopicModal', 'modalContainer')">
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 transition-all scale-95 opacity-0 duration-300" id="modalContainer">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Add New Topic</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create a new searchable topic.</p>
                </div>
                <button onclick="closeModal('addTopicModal', 'modalContainer')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('admin.topics.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-normal text-slate-700 dark:text-slate-300 mb-1.5">Topic Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="addTopicName" required placeholder="e.g. আইন আদালত" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:ring-1 focus:ring-indigo-500 transition-all outline-none text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-normal text-slate-700 dark:text-slate-300 mb-1.5">Slug</label>
                    <input type="text" name="slug" id="addTopicSlug" placeholder="auto-generated from name if left empty" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:ring-1 focus:ring-indigo-500 transition-all outline-none text-slate-500 dark:text-slate-400">
                </div>
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeModal('addTopicModal', 'modalContainer')" class="flex-1 px-5 py-2.5 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-normal rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all text-sm">Cancel</button>
                    <button type="submit" class="flex-1 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-normal rounded-lg transition-all shadow-md text-sm">Save Topic</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT TOPIC MODAL --}}
<div id="editTopicModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('editTopicModal', 'editModalContainer')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4" onclick="modalBackdropClose(event, 'editTopicModal', 'editModalContainer')">
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 transition-all scale-95 opacity-0 duration-300" id="editModalContainer">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Edit Topic</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Modify the details of this topic.</p>
                </div>
                <button onclick="closeModal('editTopicModal', 'editModalContainer')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="editTopicForm" action="" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-normal text-slate-700 dark:text-slate-300 mb-1.5">Topic Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="editTopicName" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-sm focus:ring-1 focus:ring-indigo-500 transition-all outline-none text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-normal text-slate-700 dark:text-slate-300 mb-1.5">Slug</label>
                    <input type="text" name="slug" id="editTopicSlug" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs focus:ring-1 focus:ring-indigo-500 transition-all outline-none text-slate-500 dark:text-slate-400">
                </div>
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeModal('editTopicModal', 'editModalContainer')" class="flex-1 px-5 py-2.5 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-normal rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all text-sm">Cancel</button>
                    <button type="submit" class="flex-1 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-normal rounded-lg transition-all shadow-md text-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, name, slug) {
        document.getElementById('editTopicName').value = name;
        document.getElementById('editTopicSlug').value = slug;
        document.getElementById('editTopicForm').action = '/admin/topics/' + id;
        openModal('editTopicModal', 'editModalContainer');
    }

    function slugify(str) {
        return str
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    // Auto-generate slug on create modal
    (function () {
        const nameInput = document.getElementById('addTopicName');
        const slugInput = document.getElementById('addTopicSlug');
        if (!nameInput || !slugInput) return;

        let slugManuallyChanged = false;
        slugInput.addEventListener('input', function () {
            slugManuallyChanged = this.value.length > 0;
        });

        nameInput.addEventListener('input', function () {
            if (!slugManuallyChanged || slugInput.value.length === 0) {
                slugInput.value = slugify(nameInput.value);
            }
        });
    })();

    // Attach click handlers to edit buttons
    (function () {
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.topic-edit-btn');
            if (btn) {
                const id = btn.dataset.id;
                const name = btn.dataset.name || '';
                const slug = btn.dataset.slug || '';
                openEditModal(id, name, slug);
            }
        });
    })();
</script>
@endpush

@endsection
