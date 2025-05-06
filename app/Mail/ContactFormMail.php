<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Les données du formulaire de contact
     *
     * @var array
     */
    public $data;

    /**
     * Créer une nouvelle instance de message.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Construire le message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nouveau message : ' . $this->data['object_message'])
                    ->markdown('emails.contact')
                    ->with([
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'message' => $this->data['message'],
                        'object_message' => $this->data['object_message'],
                    ]);
    }
} 