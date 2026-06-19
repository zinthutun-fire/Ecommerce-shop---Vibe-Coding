@extends('admin.layouts.admin')
@section('title', 'Users')
@section('header', 'Users')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <table class="w-full" id="users-table">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left py-3 px-4">Name</th>
                <th class="text-left py-3 px-4">Email</th>
                <th class="text-left py-3 px-4">Role</th>
                <th class="text-left py-3 px-4">Orders</th>
                <th class="text-left py-3 px-4">Joined</th>
                <th class="text-left py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $user->orders_count }}</td>
                    <td class="py-3 px-4 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="py-3 px-4"><a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:underline">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
@push('scripts-extra')
<script>$(document).ready(function(){$('#users-table').DataTable({paging:false,info:false,order:[]})});</script>
@endpush
