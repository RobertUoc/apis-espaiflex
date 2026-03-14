<?php

namespace App\Mail;
use Illuminate\Mail\Mailable;

class FacturaMail extends Mailable
{
    public $factura;
    private $pdf;

    public function __construct($factura, $pdf)
    {
        $this->factura = $factura;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Factura Nº ' . $this->factura->id)
            ->view('emails.factura')
            ->attachData($this->pdf, 'factura.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}