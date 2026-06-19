@extends('layouts.app')

@section('title', 'Profile - ShopHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Profile</h1>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Profile Info</h2>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full border rounded px-3 py-2 bg-gray-50">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="w-full border rounded px-3 py-2">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Update</button>
            </form>
        </div>
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Addresses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($addresses as $address)
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold capitalize">{{ $address->type }}</span>
                                @if($address->is_default)<span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded">Default</span>@endif
                            </div>
                            <p class="text-sm text-gray-600">{{ $address->name }}</p>
                            <p class="text-sm text-gray-600">{{ $address->full_address }}</p>
                            <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                            <div class="mt-2 flex space-x-2">
                                <button onclick="editAddress({{ $address->id }})" class="text-sm text-indigo-600 hover:underline">Edit</button>
                                <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Delete this address?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button onclick="document.getElementById('address-form').classList.toggle('hidden')" class="mt-4 text-indigo-600 hover:underline">+ Add New Address</button>
                <form action="{{ route('profile.addresses.store') }}" method="POST" id="address-form" class="hidden mt-4 border-t pt-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" class="w-full border rounded px-3 py-2">
                                <option value="shipping">Shipping</option>
                                <option value="billing">Billing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Street</label>
                            <input type="text" name="street" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">State</label>
                            <input type="text" name="state" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ZIP Code</label>
                            <input type="text" name="zip" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="country" value="US" class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_default" class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Set as default</span>
                        </label>
                    </div>
                    <button type="submit" class="mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Save Address</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
