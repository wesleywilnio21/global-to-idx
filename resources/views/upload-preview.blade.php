<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Screenshot Preview — MacroSectors AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 text-slate-800">
    <div class="max-w-xl w-full bg-white border border-slate-200 rounded-3xl p-8 shadow-sm text-center">
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-blue-200">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 mb-2">Kirim Screenshot Preview</h1>
        <p class="text-sm text-slate-500 mb-6">
            Tekan <kbd class="px-2 py-1 bg-slate-100 border border-slate-300 rounded-lg text-xs font-mono font-bold text-slate-700">Ctrl + V</kbd> langsung di halaman ini untuk paste screenshot, atau klik area di bawah:
        </p>

        <!-- Drop / Click Zone -->
        <label for="fileInput" class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl p-8 flex flex-col items-center justify-center cursor-pointer bg-slate-50/60 hover:bg-blue-50/30 transition-all group">
            <input type="file" id="fileInput" accept="image/*" class="hidden" />
            <svg class="w-10 h-10 text-slate-400 group-hover:text-blue-600 mb-3 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700">Klik untuk upload file gambar</span>
            <span class="text-xs text-slate-400 mt-1">PNG, JPG, JPEG, WEBP (Bisa langsung Ctrl + V dari clipboard)</span>
        </label>

        <!-- Status & Preview Area -->
        <div id="statusArea" class="mt-6 hidden">
            <div id="loading" class="flex items-center justify-center gap-2 text-sm text-blue-600 font-semibold py-3">
                <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <span>Mengunggah screenshot...</span>
            </div>
            <div id="successMessage" class="hidden p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-medium">
                <p class="font-bold flex items-center justify-center gap-1.5 text-emerald-700 mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Screenshot Berhasil Diupload!
                </p>
                <p class="text-xs text-emerald-600">File tersimpan di <code class="font-mono bg-white/70 px-1.5 py-0.5 rounded">public/screenshot.png</code>. Beri tahu Antigravity di terminal bahwa foto sudah terunggah!</p>
            </div>
            <img id="previewImg" class="mt-4 rounded-xl border border-slate-200 max-h-72 mx-auto object-contain hidden shadow-xs" />
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <a href="/" class="hover:text-blue-600 font-semibold flex items-center gap-1">&larr; Kembali ke Dashboard</a>
            <span>MacroSectors AI Debugger</span>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const statusArea = document.getElementById('statusArea');
        const loading = document.getElementById('loading');
        const successMessage = document.getElementById('successMessage');
        const previewImg = document.getElementById('previewImg');

        function uploadFile(file) {
            if (!file || !file.type.startsWith('image/')) return;

            statusArea.classList.remove('hidden');
            loading.classList.remove('hidden');
            successMessage.classList.add('hidden');
            previewImg.classList.add('hidden');

            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
            };
            reader.readAsDataURL(file);

            const formData = new FormData();
            formData.append('screenshot', file);

            fetch('/upload-preview', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');
                if (data.success) {
                    successMessage.classList.remove('hidden');
                } else {
                    alert('Gagal mengunggah: ' + (data.message || 'Error tidak diketahui'));
                }
            })
            .catch(err => {
                loading.classList.add('hidden');
                alert('Gagal mengunggah gambar. Silakan coba lagi.');
            });
        }

        // Handle Paste (Ctrl + V) anywhere on page
        window.addEventListener('paste', (e) => {
            const items = (e.clipboardData || e.originalEvent.clipboardData).items;
            for (let item of items) {
                if (item.type.indexOf('image') !== -1) {
                    const blob = item.getAsFile();
                    uploadFile(blob);
                    break;
                }
            }
        });

        // Handle File Input Change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                uploadFile(e.target.files[0]);
            }
        });
    </script>
</body>
</html>
