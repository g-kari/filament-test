@if($getRecord() && $getRecord()->upload_url)
    <div class="rounded-lg border border-gray-200 p-4 bg-white">
        <div class="flex items-center justify-center">
            <img 
                src="{{ $getRecord()->upload_url }}" 
                alt="{{ $getRecord()->original_filename }}"
                class="max-w-full max-h-64 rounded-lg shadow-sm"
                style="object-fit: contain;"
            />
        </div>
        <div class="mt-3 text-sm text-gray-600">
            <p><strong>ファイル名:</strong> {{ $getRecord()->original_filename }}</p>
            <p><strong>サイズ:</strong> {{ $getRecord()->dimensions ?? 'N/A' }}</p>
            <p><strong>ファイルサイズ:</strong> {{ $getRecord()->formatted_file_size }}</p>
            <p><strong>タイプ:</strong> {{ $getRecord()->mimetype }}</p>
        </div>
    </div>
@else
    <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
        <p class="text-gray-500 text-center">画像がありません</p>
    </div>
@endif