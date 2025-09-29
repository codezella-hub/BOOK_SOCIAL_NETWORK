<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\ApprovalStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{

    /**
     * Display a listing of user's donations.
     */
    public function index()
    {
        // Ensure user is authenticated before accessing donations
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your donations.');
        }

        $donations = $user->donations()->latest()->paginate(10);
        return view('user.donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new donation.
     */
    public function create()
    {
        return view('user.donations.create');
    }

    /**
     * Store a newly created donation in storage.
     */
    public function store(Request $request)
    {
        // Ensure user is authenticated
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to create a donation.');
        }

        $validated = $request->validate([
            'book_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'condition' => 'required|in:excellent,good,fair,poor',
            'book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['user_id'] = $userId;

        // Handle image upload
        if ($request->hasFile('book_image')) {
            $validated['book_image'] = $request->file('book_image')->store('donations', 'public');
        }

        $donation = Donation::create($validated);

        return redirect()->route('user.donations.index')
            ->with('success', 'Book donation submitted successfully! It will be reviewed by admin.');
    }

    /**
     * Display the specified donation.
     */
    public function show(Donation $donation)
    {
        // Ensure user is authenticated
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to view donation details.');
        }

        // Check if user owns this donation
        if ($donation->user_id !== $userId) {
            abort(403, 'You are not authorized to view this donation.');
        }

        return view('user.donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified donation.
     */
    public function edit(Donation $donation)
    {
        // Ensure user is authenticated
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to edit donations.');
        }

        // Check if user owns this donation and it's still pending
        if ($donation->user_id !== $userId) {
            abort(403, 'You are not authorized to edit this donation.');
        }

        if (!$donation->approvalStatus || $donation->approvalStatus->status !== 'pending') {
            return redirect()->route('user.donations.index')
                ->with('error', 'Only pending donations can be edited.');
        }

        return view('user.donations.edit', compact('donation'));
    }

    /**
     * Update the specified donation in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        // Ensure user is authenticated
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to update donations.');
        }

        // Check if user owns this donation and it's still pending
        if ($donation->user_id !== $userId) {
            abort(403, 'You are not authorized to update this donation.');
        }

        if (!$donation->approvalStatus || $donation->approvalStatus->status !== 'pending') {
            return redirect()->route('user.donations.index')
                ->with('error', 'Only pending donations can be updated.');
        }

        $validated = $request->validate([
            'book_title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'condition' => 'required|in:excellent,good,fair,poor',
            'book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('book_image')) {
            // Delete old image if exists
            if ($donation->book_image) {
                Storage::disk('public')->delete($donation->book_image);
            }
            $validated['book_image'] = $request->file('book_image')->store('donations', 'public');
        }

        $donation->update($validated);

        return redirect()->route('user.donations.index')
            ->with('success', 'Donation updated successfully!');
    }

    /**
     * Remove the specified donation from storage.
     */
    public function destroy(Donation $donation)
    {
        // Ensure user is authenticated
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please log in to delete donations.');
        }

        // Check if user owns this donation and it's still pending
        if ($donation->user_id !== $userId) {
            abort(403, 'You are not authorized to delete this donation.');
        }

        if (!$donation->approvalStatus || $donation->approvalStatus->status !== 'pending') {
            return redirect()->route('user.donations.index')
                ->with('error', 'Only pending donations can be deleted.');
        }

        // Delete image if exists
        if ($donation->book_image) {
            Storage::disk('public')->delete($donation->book_image);
        }

        $donation->delete();

        return redirect()->route('user.donations.index')
            ->with('success', 'Donation deleted successfully!');
    }

    // Admin methods

    /**
     * Display all donations for admin
     */
    public function adminIndex(Request $request)
    {
        $query = Donation::with(['user', 'approvalStatus.approvedBy']);

        // Get stats for all donations (not filtered)
        $stats = [
            'total' => Donation::count(),
            'pending' => Donation::pending()->count(),
            'approved' => Donation::approved()->count(),
            'rejected' => Donation::rejected()->count(),
        ];

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->whereHas('approvalStatus', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $donations = $query->latest()->paginate(15);
        
        return view('admin.donations.index', compact('donations', 'stats'));
    }

    /**
     * Show donation details for admin
     */
    public function adminShow(Donation $donation)
    {
        $donation->load(['user', 'approvalStatus.approvedBy']);
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Approve a donation
     */
    public function approve(Request $request, Donation $donation)
    {
        // Ensure admin is authenticated
        $adminId = Auth::id();
        if (!$adminId) {
            return redirect()->route('login')->with('error', 'Please log in to approve donations.');
        }

        // Create or update approval status
        $donation->approvalStatus()->updateOrCreate(
            ['donation_id' => $donation->id],
            [
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $adminId,
                'admin_notes' => $request->admin_notes
            ]
        );

        return back()->with('success', 'Donation approved successfully!');
    }

    /**
     * Reject a donation
     */
    public function reject(Request $request, Donation $donation)
    {
        // Ensure admin is authenticated
        $adminId = Auth::id();
        if (!$adminId) {
            return redirect()->route('login')->with('error', 'Please log in to reject donations.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string'
        ]);

        // Create or update approval status
        $donation->approvalStatus()->updateOrCreate(
            ['donation_id' => $donation->id],
            [
                'status' => 'rejected',
                'approved_by' => $adminId,
                'admin_notes' => $validated['admin_notes']
            ]
        );

        return back()->with('success', 'Donation rejected successfully!');
    }
}
