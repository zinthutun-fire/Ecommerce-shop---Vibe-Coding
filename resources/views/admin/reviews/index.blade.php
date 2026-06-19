@extends('admin.layouts.admin')
@section('title', 'Reviews')
@section('header', 'Reviews')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <table class="w-full" id="reviews-table">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left py-3 px-4">Product</th>
                <th class="text-left py-3 px-4">User</th>
                <th class="text-left py-3 px-4">Rating</th>
                <th class="text-left py-3 px-4">Review</th>
                <th class="text-left py-3 px-4">Status</th>
                <th class="text-left py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $review->product->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4">{{ $review->user->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4 text-yellow-500">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                        @endfor
                    </td>
                    <td class="py-3 px-4 max-w-xs">
                        @if($review->title)<strong>{{ $review->title }}</strong><br>@endif
                        {{ Str::limit($review->body, 60) }}
                    </td>
                    <td class="py-3 px-4">{!! $review->is_approved ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Approved</span>' : '<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>' !!}</td>
                    <td class="py-3 px-4 space-x-2">
                        @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">@csrf<button type="submit" class="text-green-600 hover:underline text-sm">Approve</button></form>
                        @endif
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline text-sm">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $reviews->links() }}</div>
</div>
@endsection
@push('scripts-extra')
<script>$(document).ready(function(){$('#reviews-table').DataTable({paging:false,info:false,order:[]})});</script>
@endpush
