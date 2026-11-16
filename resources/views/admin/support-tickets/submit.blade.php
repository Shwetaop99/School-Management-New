@extends('layouts.app')

@section('title', 'Create Support Request')
@section('page-title', 'Create Support Request')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Create a New Support Request</h4>
            <p>Describe your issue or query for the Super Admin.</p>
        </div>

        <div class="card-body">
            <form action="{{ route('support-tickets.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Subject</label>
                    <input type="text" name="subject"
                           class="form-control"
                           value="{{ old('subject') }}" required>
                    @error('subject')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Category</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select category</option>
                        <option value="technical">Technical</option>
                        <option value="login">Login & Security</option>
                        <option value="billing">Billing</option>
                        <option value="student_management">Student Management</option>
                        <option value="backup">Backup</option>
                        <option value="general">General</option>
                        <option value="complaint">Complaint</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Priority</label>
                    <select name="priority" class="form-control" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="5"
                              required>{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Submit Request
                </button>
            </form>
        </div>
    </div>
</div>

@endsection