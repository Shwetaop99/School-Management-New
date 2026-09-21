@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h1>📚 Add New Book</h1>
        <p>Add a new book to the school library.</p>
    </div>

    <a href="{{ route('admin.library.books.index') }}" class="back-btn">
        ← Back to Books
    </a>
</div>

<div class="book-form-card">

    <form action="{{ route('admin.library.books.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="form-section">
            <h3>Book Information</h3>

            <div class="form-grid">

                <div class="form-group full">
                    <label>Book Title <span>*</span></label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Enter book title"
                           required>
                    @error('title')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Author <span>*</span></label>
                    <input type="text"
                           name="author"
                           value="{{ old('author') }}"
                           placeholder="Enter author name"
                           required>
                    @error('author')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>ISBN</label>
                    <input type="text"
                           name="isbn"
                           value="{{ old('isbn') }}"
                           placeholder="Enter ISBN number">
                    @error('isbn')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Category <span>*</span></label>
                    <input type="text"
                           name="category"
                           value="{{ old('category') }}"
                           placeholder="e.g. Science, Fiction"
                           required>
                    @error('category')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Publisher</label>
                    <input type="text"
                           name="publisher"
                           value="{{ old('publisher') }}"
                           placeholder="Enter publisher">
                </div>

                <div class="form-group">
                    <label>Language</label>
                    <input type="text"
                           name="language"
                           value="{{ old('language') }}"
                           placeholder="e.g. English">
                </div>

                <div class="form-group">
                    <label>Publication Date</label>
                    <input type="date"
                           name="publication_date"
                           value="{{ old('publication_date') }}">
                </div>

            </div>
        </div>

        <div class="form-section">
            <h3>Inventory Details</h3>

            <div class="form-grid">

                <div class="form-group">
                    <label>Total Quantity <span>*</span></label>
                    <input type="number"
                           name="quantity"
                           value="{{ old('quantity', 1) }}"
                           min="1"
                           required>
                    @error('quantity')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Shelf Number</label>
                    <input type="text"
                           name="shelf_number"
                           value="{{ old('shelf_number') }}"
                           placeholder="e.g. A-12">
                </div>

            </div>
        </div>

        <div class="form-section">
            <h3>Additional Details</h3>

            <div class="form-grid">

                <div class="form-group">
    <label>Book Cover</label>

    <input type="file"
           id="cover_image"
           accept="image/*">

    <input type="hidden"
           name="cover_image"
           id="cover_image_url">

    <small id="upload-status" class="upload-status"></small>

    <div id="cover-preview" class="cover-preview"></div>

    @error('cover_image')
        <small class="error">{{ $message }}</small>
    @enderror
</div>

                <div class="form-group">
                    <label>Status <span>*</span></label>
                    <select name="status" required>
                        <option value="Available"
                            {{ old('status', 'Available') == 'Available' ? 'selected' : '' }}>
                            Available
                        </option>

                        <option value="Unavailable"
                            {{ old('status') == 'Unavailable' ? 'selected' : '' }}>
                            Unavailable
                        </option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description"
                              rows="5"
                              placeholder="Enter a short description about the book...">{{ old('description') }}</textarea>
                </div>

            </div>
        </div>

        <div class="form-actions">

            <a href="{{ route('admin.library.books.index') }}"
               class="cancel-btn">
                Cancel
            </a>

            <button type="submit" class="save-btn">
                + Add Book
            </button>

        </div>

    </form>

</div>

<style>

    .upload-status {
    display: block;
    margin-top: 7px;
    font-size: 12px;
    color: #697386;
}

.cover-preview {
    display: none;
    margin-top: 12px;
}

.cover-preview img {
    width: 100px;
    height: 130px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dfe4ec;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.page-header h1 {
    margin: 0;
    color: #172033;
    font-size: 25px;
    font-weight: 700;
}

.page-header p {
    margin: 6px 0 0;
    color: #7b8494;
    font-size: 13px;
}

.back-btn {
    text-decoration: none;
    padding: 10px 16px;
    border: 1px solid #dfe4ec;
    border-radius: 9px;
    background: #fff;
    color: #344054;
    font-size: 13px;
    font-weight: 600;
}

.book-form-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(30, 50, 80, 0.07);
    overflow: hidden;
}

.form-section {
    padding: 24px;
    border-bottom: 1px solid #edf0f5;
}

.form-section h3 {
    margin: 0 0 20px;
    font-size: 16px;
    color: #172033;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    margin-bottom: 7px;
    font-size: 13px;
    font-weight: 600;
    color: #344054;
}

.form-group label span {
    color: #e53935;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #dfe4ec;
    border-radius: 9px;
    background: #fff;
    padding: 11px 13px;
    outline: none;
    color: #273247;
    font-size: 13px;
    font-family: inherit;
    transition: 0.2s;
}

.form-group input,
.form-group select {
    height: 44px;
}

.form-group textarea {
    resize: vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.08);
}

.form-group input[type="file"] {
    padding: 9px 12px;
}

.error {
    color: #d32f2f;
    margin-top: 5px;
    font-size: 12px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 24px;
    background: #fafbfc;
}

.cancel-btn,
.save-btn {
    height: 42px;
    padding: 0 18px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.cancel-btn {
    border: 1px solid #dfe4ec;
    background: #fff;
    color: #344054;
}

.save-btn {
    border: none;
    background: #1976d2;
    color: #fff;
}

.save-btn:hover {
    background: #1565c0;
}

.cancel-btn:hover,
.back-btn:hover {
    background: #f5f7fa;
}

@media (max-width: 768px) {

    .page-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .cancel-btn,
    .save-btn {
        width: 100%;
    }
}

</style>

<script>
document.getElementById('cover_image').addEventListener('change', async function () {

    const file = this.files[0];

    if (!file) return;

    const status = document.getElementById('upload-status');
    const preview = document.getElementById('cover-preview');
    const urlInput = document.getElementById('cover_image_url');

    try {
        status.style.color = '#1976d2';
        status.textContent = 'Compressing cover...';

        const image = new Image();
        const objectUrl = URL.createObjectURL(file);
        image.src = objectUrl;

        await new Promise((resolve, reject) => {
            image.onload = resolve;
            image.onerror = reject;
        });

        const maxWidth = 1000;
        const maxHeight = 1400;
        let width = image.width;
        let height = image.height;

        if (width > maxWidth || height > maxHeight) {
            const ratio = Math.min(maxWidth / width, maxHeight / height);
            width = Math.round(width * ratio);
            height = Math.round(height * ratio);
        }

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;

        const ctx = canvas.getContext('2d');
        if (!ctx) throw new Error('Your browser does not support image compression.');

        ctx.drawImage(image, 0, 0, width, height);

        const blob = await new Promise(resolve => {
            canvas.toBlob(resolve, 'image/jpeg', 0.80);
        });

        URL.revokeObjectURL(objectUrl);

        if (!blob) throw new Error('Image compression failed.');

        const compressedFile = new File(
            [blob],
            'book-cover.jpg',
            { type: 'image/jpeg' }
        );

        const sizeKB = Math.round(compressedFile.size / 1024);
        status.textContent = `Compressed to ${sizeKB} KB. Uploading...`;

        const cloudName = '{{ config('services.cloudinary.cloud_name') }}';
        const uploadPreset = '{{ config('services.cloudinary.upload_preset') }}';
        const uploadUrl = `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`;

        console.log('Cloudinary Upload URL:', uploadUrl);
        console.log('Cloudinary Upload Preset:', uploadPreset);

        const formData = new FormData();
        formData.append('file', compressedFile);
        formData.append('upload_preset', uploadPreset);

        const response = await fetch(uploadUrl, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        console.log('Cloudinary response:', data);

        if (!response.ok) {
            throw new Error(
                data.error?.message ||
                response.headers.get('X-Cld-Error') ||
                'Cloudinary upload failed.'
            );
        }

        urlInput.value = data.secure_url;

        preview.innerHTML = `
            <img src="${data.secure_url}" alt="Book Cover">
        `;
        preview.style.display = 'block';

        status.style.color = '#2e7d32';
        status.textContent = `Cover uploaded successfully (${sizeKB} KB).`;

    } catch (error) {
        console.error('Cloudinary Upload Error:', error);

        status.style.color = '#d32f2f';
        status.textContent = 'Upload failed: ' + error.message;
        urlInput.value = '';
        preview.innerHTML = '';
        preview.style.display = 'none';
    }
});
</script>

@endsection