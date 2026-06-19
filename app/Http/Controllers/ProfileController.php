<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->addresses;
        return view('frontend.account.profile', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($request->only(['name', 'phone']));
        return back()->with('success', 'Profile updated!');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'type' => 'required|in:shipping,billing',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'required|string|max:2',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            auth()->user()->addresses()->where('type', $request->type)->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create($request->all());
        return back()->with('success', 'Address added!');
    }

    public function updateAddress(Request $request, Address $address)
    {
        $request->validate([
            'type' => 'required|in:shipping,billing',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'required|string|max:2',
        ]);

        $address->update($request->all());
        return back()->with('success', 'Address updated!');
    }

    public function destroyAddress(Address $address)
    {
        $address->delete();
        return back()->with('success', 'Address deleted!');
    }
}
