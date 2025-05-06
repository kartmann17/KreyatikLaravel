<?php

namespace App\Http\Controllers;

use App\SEO\ContactPageSEO;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    protected $seo;

    public function __construct(ContactPageSEO $seo)
    {
        $this->seo = $seo;
    }

    public function index()
    {
        $seoData = $this->seo->getData();
        return view('contact.index', compact('seoData'));
    }

    /**
     * Traite l'envoi d'email du formulaire de contact
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'object_message' => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        try {
            // Enregistrer le message dans la base de données
            $contactMessage = ContactMessage::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'subject' => $validatedData['object_message'],
                'message' => $validatedData['message'],
                'ip_address' => $request->ip(),
                'is_read' => false
            ]);

            // Envoi de l'email
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($validatedData));
            
            // Si la requête est AJAX
            if ($request->ajax()) {
                return response()->json(['success' => 'Votre message a été envoyé avec succès.']);
            }
            
            // Sinon, redirection avec message flash
            return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            // Log l'erreur
            Log::error('Erreur lors de l\'envoi du message: ' . $e->getMessage());
            
            // Si la requête est AJAX
            if ($request->ajax()) {
                return response()->json(['error' => 'Une erreur est survenue lors de l\'envoi du message.'], 500);
            }
            
            // Sinon, redirection avec message d'erreur
            return redirect()->route('contact')->with('error', 'Une erreur est survenue lors de l\'envoi du message.');
        }
    }
} 