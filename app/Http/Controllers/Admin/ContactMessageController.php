<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    /**
     * Affiche la liste des messages de contact.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = ContactMessage::unread()->count();
        
        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Affiche un message de contact spécifique.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Marquer comme lu si ce n'est pas déjà fait
        if (!$message->is_read) {
            $message->markAsRead();
        }
        
        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Marque un message comme lu.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsRead();
        
        return redirect()->back()->with('success', 'Message marqué comme lu.');
    }

    /**
     * Marque plusieurs messages comme lus.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markMultipleAsRead(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Aucun message sélectionné.');
        }
        
        ContactMessage::whereIn('id', $ids)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return redirect()->back()->with('success', count($ids) . ' message(s) marqué(s) comme lu(s).');
    }

    /**
     * Supprime un message.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message supprimé avec succès.');
    }

    /**
     * Récupère le nombre de messages non lus pour les notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        $count = ContactMessage::unread()->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Répond à un message de contact.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, $id)
    {
        \Log::info('Méthode reply appelée avec l\'ID: ' . $id);
        \Log::info('Données de requête:', $request->all());
        
        try {
            $message = ContactMessage::findOrFail($id);
            
            $validatedData = $request->validate([
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
            ]);
            
            \Log::info('Données validées:', $validatedData);
            \Log::info('Configuration mail:', [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]);
            
            // Envoyer l'email
            Mail::raw($request->input('content'), function ($mail) use ($request, $message) {
                $mail->to($message->email, $message->name)
                    ->subject($request->input('subject'));
                
                // Utiliser l'email du système comme expéditeur
                $mail->from(config('mail.from.address', 'noreply@example.com'), config('mail.from.name', 'Administration'));
            });
            
            // Marquer le message comme lu s'il ne l'est pas déjà
            if (!$message->is_read) {
                $message->markAsRead();
            }
            
            \Log::info('Réponse envoyée avec succès');
            return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Message non trouvé: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Message introuvable.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur de validation: ' . $e->getMessage());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de la réponse: ' . $e->getMessage());
            \Log::error('Exception: ' . get_class($e));
            \Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de la réponse: ' . $e->getMessage());
        }
    }
}
