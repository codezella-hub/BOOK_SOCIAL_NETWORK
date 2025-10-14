<?php

namespace App\Policies;

use App\Models\Evenement;
use App\Models\User;

class EvenementPolicy
{
    /**
     * Determine whether the user can view any events (admin panel listing).
     */
    public function viewAny(?\App\Models\User $user): bool {
        // Allow only authenticated users (admins) to view the event list
        return $user !== null;
    }
    public function view(?User $user, Evenement $event): bool {
        if ($event->visibility === 'public' && $event->isPublished()) return true;
        if (!$user) return false;
        return $user->id === $event->user_id; // owner can view drafts/private
    }

    public function create(User $user): bool { return true; }

    public function update(User $user, Evenement $event): bool { return $user->id === $event->user_id; }

    public function delete(User $user, Evenement $event): bool { return $user->id === $event->user_id; }

    public function publish(User $user, Evenement $event): bool { return $user->id === $event->user_id; }

    public function cancel(User $user, Evenement $event): bool { return $user->id === $event->user_id; }

    public function rsvp(User $user, Evenement $event): bool {
        return $event->isPublished() && $event->status !== 'cancelled';
    }

    public function manageParticipants(User $user, Evenement $event): bool {
        return $user->id === $event->user_id;
    }

    public function downloadTicket(User $user, \App\Models\Ticket $ticket): bool
        {
            $allowed = $user->id === $ticket->user_id && $ticket->evenement_id !== null;

            \Log::info('Policy: downloadTicket', [
                'auth_user_id' => $user->id,
                'ticket_id' => $ticket->id,
                'ticket_user_id' => $ticket->user_id,
                'ticket_event_id' => $ticket->evenement_id,
                'allowed' => $allowed
            ]);

            if (!$allowed) {
                \Log::warning('Ticket download denied', [
                    'auth_user_id' => $user->id,
                    'ticket_id' => $ticket->id,
                    'ticket_user_id' => $ticket->user_id,
                    'ticket_event_id' => $ticket->evenement_id,
                ]);
            }

            return $allowed;
        }
}
