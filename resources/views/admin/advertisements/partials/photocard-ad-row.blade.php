@php
    $adImage = optional($category->photocardAd)->image;
    $adUrl = $adImage ? storage_image_url($adImage) : '';
@endphp
<tr class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/40">
    <td class="py-3 px-4 text-center text-xs text-slate-600 dark:text-slate-300">{{ $row }}</td>
    <td class="py-3 px-4">
        <div class="flex items-center gap-2 {{ $indent ? 'pl-6' : '' }}">
            <span class="w-2 h-2 rounded-full {{ $indent ? 'bg-slate-300' : 'bg-indigo-500' }} shrink-0"></span>
            <span class="text-sm font-normal text-black dark:text-white">{{ $category->name }}</span>
            @if($indent)
                <span class="text-[10px] text-slate-400">sub</span>
            @endif
        </div>
    </td>
    <td class="py-3 px-4">
        @if($adUrl)
            <img src="{{ $adUrl }}" alt="{{ $category->name }}" class="h-10 w-28 object-cover rounded border border-slate-200 dark:border-slate-700 bg-slate-100">
        @else
            <span class="text-xs text-slate-400">কোনো ইমেজ নেই</span>
        @endif
    </td>
    <td class="py-3 px-4">
        <form action="{{ route('admin.advertisements.photocard-ads.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="image" accept="image/*" required class="block w-full max-w-[220px] text-[11px] text-slate-600 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-slate-300 dark:file:bg-indigo-500/10 dark:file:text-indigo-300">
            <button type="submit" class="shrink-0 px-3 py-1.5 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
        </form>
    </td>
    <td class="py-3 px-4 text-right">
        <div class="flex items-center justify-end gap-1">
            @if($adUrl)
            <button
                type="button"
                class="px-2.5 py-1.5 text-xs font-medium rounded-md text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-500/10"
                data-photocard-preview
                data-src="{{ $adUrl }}"
                data-name="{{ $category->name }}"
                data-auto-open="{{ (int) $previewCategoryId === (int) $category->id ? '1' : '0' }}"
            >Preview</button>
            <form action="{{ route('admin.advertisements.photocard-ads.destroy', $category->id) }}" method="POST" onsubmit="return confirm('এই ক্যাটাগরির ফটোকার্ড অ্যাড মুছবেন?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-2.5 py-1.5 text-xs font-medium rounded-md text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10">Remove</button>
            </form>
            @endif
        </div>
    </td>
</tr>
