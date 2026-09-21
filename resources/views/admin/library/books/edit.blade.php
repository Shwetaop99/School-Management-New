@extends('layouts.app')

@section('content')

<div class="edit-book-page">

    <div class="page-header">
        <div>
            <h1>Edit Book</h1>
            <p>Update the information of this library book.</p>
        </div>

        <a href="{{ route('admin.library.books.show', $book) }}" class="back-btn">
            ← Back to Book
        </a>
    </div>

    <div class="book-form-card">

        <form action="{{ route('admin.library.books.update', $book) }}"
              method="POST">

            @csrf
            @method('PUT')

            {{-- Book Information --}}
            <div class="form-section">
                <h3>Book Information</h3>

                <div class="form-grid">

                    <div class="form-group full">
                        <label>Book Title <span>*</span></label>
                        <input type="text"
                               name="title"
                               value="{{ old('title', $book->title) }}"
                               required>
                        @error('title')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Author <span>*</span></label>
                        <input type="text"
                               name="author"
                               value="{{ old('author', $book->author) }}"
                               required>
                        @error('author')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>ISBN</label>
                        <input type="text"
                               name="isbn"
                               value="{{ old('isbn', $book->isbn) }}">
                        @error('isbn')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Category <span>*</span></label>
                        <input type="text"
                               name="category"
                               value="{{ old('category', $book->category) }}"
                               required>
                        @error('category')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Publisher</label>
                        <input type="text"
                               name="publisher"
                               value="{{ old('publisher', $book->publisher) }}">
                    </div>

                    <div class="form-group">
                        <label>Language</label>
                        <input type="text"
                               name="language"
                               value="{{ old('language', $book->language) }}">
                    </div>

                    <div class="form-group">
                        <label>Publication Date</label>
                        <input type="date"
                               name="publication_date"
                               value="{{ old(
                                   'publication_date',
                                   $book->publication_date?->format('Y-m-d')
                               ) }}">
                    </div>

                </div>
            </div>


            {{-- Inventory --}}
            <div class="form-section">
                <h3>Inventory Details</h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Total Quantity <span>*</span></label>
                        <input type="number"
                               name="quantity"
                               value="{{ old('quantity', $book->quantity) }}"
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
                               value="{{ old('shelf_number', $book->shelf_number) }}">
                    </div>

                </div>
            </div>


            {{-- Cover --}}
            <div class="form-section">
                <h3>Book Cover</h3>

                @if($book->cover_image)
                    <div class="current-cover">
                        <span>Current Cover</span>

                        <img src="{{ $book->cover_image }}"
                             alt="{{ $book->title }}">
                    </div>
                @endif

                <div class="form-group">

                    <label>New Cover Image</label>

                    <input type="file"
                           id="cover_image"
                           accept="image/*">

                    <input type="hidden"
                           name="cover_image"
                           id="cover_image_url"
                           value="{{ old('cover_image', $book->cover_image) }}">

                    <small id="upload-status" class="upload-status">
                        Select a new image only if you want to replace the current cover.
                    </small>

                    <div id="cover-preview" class="cover-preview"></div>

                    @error('cover_image')
                        <small class="error">{{ $message }}</small>
                    @enderror

                </div>
            </div>


            {{-- Other Details --}}
            <div class="form-section">
                <h3>Additional Details</h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label>Status <span>*</span></label>

                        <select name="status" required>
                            <option value="Available"
                                {{ old('status', $book->status) === 'Available' ? 'selected' : '' }}>
                                Available
                            </option>

                            <option value="Unavailable"
                                {{ old('status', $book->status) === 'Unavailable' ? 'selected' : '' }}>
                                Unavailable
                            </option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Description</label>

                        <textarea name="description"
                                  rows="5"
                                  placeholder="Enter book description...">{{ old('description', $book->description) }}</textarea>
                    </div>

                </div>
            </div>


            {{-- Actions --}}
            <div class="form-actions">

                <a href="{{ route('admin.library.books.show', $book) }}"
                   class="cancel-btn">
                    Cancel
                </a>

                <button type="submit" class="save-btn">
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>


<style>

.edit-book-page {
    padding: 10px 5px;
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
    height: 42px;
    padding: 0 16px;
    border: 1px solid #dfe4ec;
    border-radius: 9px;
    background: #fff;
    color: #344054;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
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

.current-cover {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.current-cover span {
    color: #7b8497;
    font-size: 12px;
}

.current-cover img {
    width: 70px;
    height: 90px;
    object-fit: cover;
    border-radius: 7px;
    border: 1px solid #dfe4ec;
}

.upload-status {
    display: block;
    margin-top: 7px;
    color: #697386;
    font-size: 12px;
}

.cover-preview {
    display: none;
    margin-top: 12px;
}

.cover-preview img {
    width: 90px;
    height: 115px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dfe4ec;
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

    if (!file) {
        return;
    }

    const status = document.getElementById('upload-status');
    const preview = document.getElementById('cover-preview');
    const urlInput = document.getElementById('cover_image_url');

    try {

        status.style.color = '#1976d2';
        status.textContent = 'Compressing new cover...';

        /*
        |--------------------------------------------------------------------------
        | Load Image
        |--------------------------------------------------------------------------
        */

        const image = new Image();
        const objectUrl = URL.createObjectURL(file);

        image.src = objectUrl;

        await new Promise((resolve, reject) => {
            image.onload = resolve;
            image.onerror = reject;
        });

        /*
        |--------------------------------------------------------------------------
        | Resize Image
        |--------------------------------------------------------------------------
        */

        const maxWidth = 1000;
        const maxHeight = 1400;

        let width = image.width;
        let height = image.height;

        if (width > maxWidth || height > maxHeight) {

            const ratio = Math.min(
                maxWidth / width,
                maxHeight / height
            );

            width = Math.round(width * ratio);
            height = Math.round(height * ratio);
        }

        /*
        |--------------------------------------------------------------------------
        | Canvas Compression
        |--------------------------------------------------------------------------
        */

        const canvas = document.createElement('canvas');

        canvas.width = width;
        canvas.height = height;

        const ctx = canvas.getContext('2d');

        if (!ctx) {
            throw new Error('Your browser does not support image compression.');
        }

        ctx.drawImage(
            image,
            0,
            0,
            width,
            height
        );

        /*
        |--------------------------------------------------------------------------
        | Convert To JPEG
        |--------------------------------------------------------------------------
        */

        const blob = await new Promise(resolve => {

            canvas.toBlob(
                resolve,
                'image/jpeg',
                0.80
            );

        });

        URL.revokeObjectURL(objectUrl);

        if (!blob) {
            throw new Error('Image compression failed.');
        }

        /*
        |--------------------------------------------------------------------------
        | Create Compressed File
        |--------------------------------------------------------------------------
        */

        const compressedFile = new File(
            [blob],
            'book-cover.jpg',
            {
                type: 'image/jpeg'
            }
        );

        const sizeKB = Math.round(compressedFile.size / 1024);

        status.textContent =
            `Compressed to ${sizeKB} KB. Uploading...`;

        /*
        |--------------------------------------------------------------------------
        | Cloudinary Configuration
        |--------------------------------------------------------------------------
        */

        const cloudName =
            '{{ config('services.cloudinary.cloud_name') }}';

        const uploadPreset =
            '{{ config('services.cloudinary.upload_preset') }}';

        const uploadUrl =
            `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`;

        /*
        |--------------------------------------------------------------------------
        | Upload To Cloudinary
        |--------------------------------------------------------------------------
        */

        const formData = new FormData();

        formData.append(
            'file',
            compressedFile
        );

        formData.append(
            'upload_preset',
            uploadPreset
        );

        const response = await fetch(
            uploadUrl,
            {
                method: 'POST',
                body: formData
            }
        );

        const data = await response.json();

        console.log('Cloudinary response:', data);

        if (!response.ok) {

            throw new Error(
                data.error?.message ||
                response.headers.get('X-Cld-Error') ||
                'Cloudinary upload failed.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Save New Cloudinary URL
        |--------------------------------------------------------------------------
        */

        urlInput.value = data.secure_url;

        /*
        |--------------------------------------------------------------------------
        | Show New Preview
        |--------------------------------------------------------------------------
        */

        preview.innerHTML = `
            <img
                src="${data.secure_url}"
                alt="New Book Cover"
            >
        `;

        preview.style.display = 'block';

        status.style.color = '#2e7d32';

        status.textContent =
            `New cover uploaded successfully (${sizeKB} KB).`;

    } catch (error) {

        console.error('Cloudinary Upload Error:', error);

        status.textContent =
            'Upload failed: ' + error.message;

        status.style.color = '#d32f2f';

        urlInput.value =
            '{{ old('cover_image', $book->cover_image) }}';

        preview.innerHTML = '';

        preview.style.display = 'none';
    }
});
</script>

@endsection