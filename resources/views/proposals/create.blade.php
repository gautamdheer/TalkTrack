@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #fff;
    }
    

    .preview-area {
        border: 2px dashed #ddd;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
</style>
@endsection

@section('content')
<div class="container">
    <div class="form-container">
        <h2 class="text-2xl font-bold mb-6 text-white">Submit Talk Proposal</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('proposals.store') }}" method="POST" enctype="multipart/form-data" id="proposalForm">
            @csrf

            <div class="mb-4">
                <label for="title" class="block mb-2 required text-white">Talk Title</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       class="w-full px-3 py-2 border rounded-lg @error('title') border-red-500 @enderror"
                       value="{{ old('title') }}"
                       required>
                @error('title')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block mb-2 required text-white">Description</label>
                <textarea name="description" 
                          id="description" 
                          rows="5" 
                          class="w-full px-3 py-2 border text-black rounded-lg @error('description') border-red-500 @enderror"
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="duration" class="block mb-2 required text-white">Duration (minutes)</label>
                <select name="duration" 
                        id="duration" 
                        class="w-full px-3 py-2 border rounded-lg @error('duration') border-red-500 @enderror"
                        required>
                    <option value="">Select duration</option>
                    <option value="15" {{ old('duration') == 15 ? 'selected' : '' }}>15 minutes</option>
                    <option value="30" {{ old('duration') == 30 ? 'selected' : '' }}>30 minutes</option>
                    <option value="45" {{ old('duration') == 45 ? 'selected' : '' }}>45 minutes</option>
                    <option value="60" {{ old('duration') == 60 ? 'selected' : '' }}>60 minutes</option>
                </select>
                @error('duration')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tags" class="block mb-2 required text-white">Tags</label>
                <select name="tags[]" 
                        id="tags" 
                        class="select2 w-full px-3 py-2 border rounded-lg @error('tags') border-red-500 @enderror"
                        multiple
                        required>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                @error('tags')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="technical_requirements" class="block mb-2 text-white">Technical Requirements</label>
                <textarea name="technical_requirements" 
                          id="technical_requirements" 
                          rows="3" 
                          class="w-full px-3 py-2 border text-black rounded-lg @error('technical_requirements') border-red-500 @enderror"
                          placeholder="List any technical requirements for your talk">{{ old('technical_requirements') }}</textarea>
                @error('technical_requirements')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="presentation_file" class="block mb-2 required text-white">Presentation File (PDF)</label>
                <div class="preview-area" id="dropZone">
                    <p class="text-white">Drag and drop your PDF here or</p>
                    <input type="file" 
                           name="presentation_file" 
                           id="presentation_file" 
                           class="mt-2"
                           accept=".pdf"
                           required>
                    <p class="text-sm text-gray-600 mt-2 text-white">Maximum file size: 10MB</p>
                </div>
                @error('presentation_file')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" 
                        onclick="window.history.back()" 
                        class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Submit Proposal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for tags
        $('#tags').select2({
            placeholder: 'Select tags',
            allowClear: true,
            tags: true
        });

        // Drag and drop functionality
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('presentation_file');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('bg-gray-100');
        }

        function unhighlight(e) {
            dropZone.classList.remove('bg-gray-100');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
        }

        // Form validation
        const form = document.getElementById('proposalForm');
        form.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            if (file && file.size > 10 * 1024 * 1024) { // 10MB in bytes
                e.preventDefault();
                alert('File size must not exceed 10MB');
            }
        });
    });
</script>
@endsection