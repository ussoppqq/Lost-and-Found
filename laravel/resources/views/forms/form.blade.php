@extends('layouts.app')

@section('title', 'Submit Found Item Report')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
    <h1 class="text-xl font-bold mb-4">Submit Found Item Report</h1>

    <form action="{{ route('found.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Name --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Name *</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        {{-- Phone / Email --}}
        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Phone</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        {{-- Location --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Location</label>
            <input type="text" name="location" class="w-full border rounded px-3 py-2">
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Description *</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
        </div>

        {{-- Photo --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Add Picture (optional)</label>
            <input type="file" name="photo" class="w-full border rounded px-3 py-2">
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-900">
            Submit Found Item Report
        </button>
    </form>
</div>
@endsection
