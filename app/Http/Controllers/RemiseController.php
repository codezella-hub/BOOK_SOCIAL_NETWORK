<?php

namespace App\Http\Controllers;

use App\Models\Remise;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RemiseController extends Controller
{
    public function create($donationId)
    {
        $donation = Donation::with('user')->findOrFail($donationId);
        
        // Vérifier que l'utilisateur connecté est bien le donateur
        if ($donation->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à planifier cette remise.');
        }

        // Vérifier qu'une remise n'existe pas déjà
        if ($donation->remise) {
            return redirect()->back()->with('error', 'Une remise est déjà planifiée pour cette donation.');
        }

        // Vérifier que la donation est approuvée
        if ($donation->status !== 'approved') {
            return redirect()->back()->with('error', 'Seules les donations approuvées peuvent faire l\'objet d\'une remise.');
        }

        // Récupérer les admins disponibles
        try {
            $admins = User::role('admin')->get();
            if ($admins->isEmpty()) {
                // Fallback : récupérer tous les utilisateurs ayant le rôle admin
                $admins = User::whereHas('roles', function($query) {
                    $query->where('name', 'admin');
                })->get();
            }
        } catch (\Exception $e) {
            // En cas d'erreur, utiliser la méthode alternative
            $admins = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->get();
        }

        return view('remise.create', compact('donation', 'admins'));
    }

    public function store(Request $request, $donationId)
    {
        $donation = Donation::findOrFail($donationId);
        
        // Vérifier que l'utilisateur connecté est bien le donateur
        if ($donation->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à planifier cette remise.');
        }

        // Validation
        $request->validate([
            'date_rendez_vous' => 'required|date|after:now',
            'lieu' => 'required|string|max:255',
            'admin_id' => 'required|exists:users,id'
        ], [
            'date_rendez_vous.required' => 'La date du rendez-vous est obligatoire.',
            'date_rendez_vous.after' => 'La date du rendez-vous doit être dans le futur.',
            'lieu.required' => 'Le lieu de remise est obligatoire.',
            'admin_id.required' => 'Vous devez sélectionner un administrateur.',
            'admin_id.exists' => 'L\'administrateur sélectionné n\'existe pas.'
        ]);

        // Vérifier que l'admin sélectionné a bien le rôle admin
        $admin = User::findOrFail($request->admin_id);
        if (!$admin->hasRole('admin')) {
            return redirect()->back()->with('error', 'L\'utilisateur sélectionné n\'est pas un administrateur.');
        }

        // Créer la remise
        Remise::create([
            'donation_id' => $donation->id,
            'user_id' => Auth::id(),
            'admin_id' => $request->admin_id,
            'date_rendez_vous' => $request->date_rendez_vous,
            'lieu' => $request->lieu,
            'statut' => Remise::STATUT_EN_ATTENTE
        ]);

        return redirect()->route('user.donations.index')->with('success', 'Remise planifiée avec succès. L\'administrateur sera notifié.');
    }

    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé.');
        }

        $remises = Remise::with(['donation.user', 'user', 'admin'])
            ->orderBy('date_rendez_vous', 'asc')
            ->paginate(15);

        return view('admin.remises.index', compact('remises'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé.');
        }

        $remise = Remise::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,prevu,effectue,annule',
            'date_rendez_vous' => 'nullable|date|after:now',
            'lieu' => 'nullable|string|max:255'
        ]);

        // Mettre à jour le statut
        $remise->statut = $request->statut;

        // Si l'admin propose une nouvelle date/lieu
        if ($request->filled('date_rendez_vous')) {
            $remise->date_rendez_vous = $request->date_rendez_vous;
        }
        
        if ($request->filled('lieu')) {
            $remise->lieu = $request->lieu;
        }

        $remise->save();

        $message = match($request->statut) {
            'prevu' => 'Remise confirmée avec succès.',
            'effectue' => 'Remise marquée comme effectuée.',
            'annule' => 'Remise annulée.',
            default => 'Statut mis à jour.'
        };

        return redirect()->back()->with('success', $message);
    }

    public function show($id)
    {
        $remise = Remise::with(['donation.user', 'user', 'admin'])->findOrFail($id);
        
        // Vérifier les permissions
        if (!Auth::user()->hasRole('admin') && $remise->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette remise.');
        }

        return view('remise.show', compact('remise'));
    }
}
