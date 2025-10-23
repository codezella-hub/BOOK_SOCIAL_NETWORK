<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized access.');
        }

        $user = Auth::user();
        
        // Vérification alternative du rôle admin
        $isAdmin = Role::where('name', 'admin')
            ->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })->exists();
            
        if (!$isAdmin) {
            abort(403, 'Unauthorized access.');
        }

        // Récupération des notifications
        $notifications = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', User::class)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unreadNotificationsCount = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->count();

        $stats = [
            'totalUsers' => User::count(),
            'totalBooks' => 8756,
            'totalReviews' => 12489,
        ];

        return view('admin.dashboard', compact('stats', 'notifications', 'unreadNotificationsCount'));
    }

    public function markNotificationAsRead(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $notification->markAsRead();

        if (isset($notification->data['report_id'])) {
            return redirect()->route('admin.reports.show', ['report' => $notification->data['report_id']]);
        }

        return redirect()->route('admin.reports.index')->with('info', 'Notification marked as read.');
    }

    /**
     * API endpoint pour récupérer les notifications (pour le temps réel)
     */
    public function getNotifications(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        
        $isAdmin = Role::where('name', 'admin')
            ->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })->exists();
            
        if (!$isAdmin) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Récupération des notifications
        $notifications = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', User::class)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'],
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => route('admin.notifications.read', ['notification' => $notification->id]),
                    'is_unread' => is_null($notification->read_at)
                ];
            });

        $unreadNotificationsCount = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', User::class)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadNotificationsCount
        ]);
    }
    public function markAllAsRead(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::user();
    
    $isAdmin = Role::where('name', 'admin')
        ->whereHas('users', function($query) use ($user) {
            $query->where('users.id', $user->id);
        })->exists();
        
    if (!$isAdmin) {
        return response()->json(['error' => 'Forbidden'], 403);
    }

    // Marquer toutes les notifications non lues comme lues
    $user->unreadNotifications->markAsRead();

    return response()->json([
        'success' => true,
        'message' => 'All notifications marked as read'
    ]);
}
}