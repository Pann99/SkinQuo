@extends('layouts.admin.admin')

@section('title', 'Feedback Monitoring — SkinQuo Admin')
@section('page_title', 'User Feedback Monitor')

@section('content')
{{-- Filter Section --}}
<div class="card-admin mb-6">
    <h3 class="text-lg font-bold text-[var(--dark-brown)] mb-4">🔍 Filter & Search</h3>
    <form method="GET" action="{{ route('admin.feedback.monitor') }}" class="flex gap-4 flex-wrap">
        <input type="text" name="search" placeholder="Search by user or product..." 
               class="input-admin flex-1 min-w-48" 
               value="{{ request('search') }}">
        
        <select name="status" class="input-admin w-48">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending Approval</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
        </select>

        <select name="rating" class="input-admin w-40">
            <option value="">All Ratings</option>
            <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Stars)</option>
            <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Stars)</option>
            <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>⭐⭐⭐ (3 Stars)</option>
            <option value="1-2" {{ request('rating') === '1-2' ? 'selected' : '' }}>⭐ (1-2 Stars)</option>
        </select>

        <button type="submit" class="btn-primary-admin">
            🔍 Search
        </button>
    </form>
</div>

{{-- Stats Section --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card-admin">
        <p class="text-gray-600 text-sm">Total Feedback</p>
        <p class="text-3xl font-bold text-[var(--dark-brown)] mt-1">0</p>
    </div>
    <div class="card-admin">
        <p class="text-gray-600 text-sm">Pending Review</p>
        <p class="text-3xl font-bold text-orange-600 mt-1">0</p>
    </div>
    <div class="card-admin">
        <p class="text-gray-600 text-sm">Approved</p>
        <p class="text-3xl font-bold text-green-600 mt-1">0</p>
    </div>
    <div class="card-admin">
        <p class="text-gray-600 text-sm">Average Rating</p>
        <p class="text-3xl font-bold text-[var(--dark-brown)] mt-1">0.0</p>
    </div>
</div>

{{-- Feedback List --}}
<div class="space-y-4">
    {{-- Sample Feedback Card (To be replaced with @foreach loop) --}}
    <div class="card-admin">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="font-semibold text-gray-800">User Name</p>
                <p class="text-sm text-gray-500">📦 Product: <span class="font-medium">Product Name</span></p>
            </div>
            <span class="badge-admin badge-warning">⏳ Pending Review</span>
        </div>

        <div class="mb-4">
            <p class="text-gray-700 font-medium">⭐⭐⭐⭐⭐ Excellent product, highly recommend!</p>
            <p class="text-gray-600 text-sm mt-2 italic">"This serum changed my skin completely. I've been using it for 2 weeks and the results are amazing. Would definitely buy again!"</p>
        </div>

        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
            <p class="text-gray-500 text-xs">Submitted on: <span class="font-medium">April 10, 2026</span></p>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition">
                    ✅ Approve
                </button>
                <button class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition">
                    ❌ Reject
                </button>
            </div>
        </div>
    </div>

    {{-- Empty State --}}
    <div class="card-admin text-center py-12">
        <p class="text-gray-500 text-lg">📭 No feedback to review</p>
        <p class="text-gray-400 text-sm mt-2">All feedback has been reviewed or there are no feedback submissions yet.</p>
    </div>
</div>

{{-- Pagination (placeholder) --}}
<div class="mt-6 flex justify-center">
    {{-- Add pagination links here --}}
</div>
@endsection
