<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use App\Models\Cms\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class Payment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'mode',
        'item',
        'amount',
        'currency',
        'message',
        'data',
        'transaction_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the parent payable model.
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the invoices associated with the payment.
     */
    public function invoices(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable')->where('field', 'invoice');
    }

    public function createInvoice()
    {
        $data = [];
        $data['civility'] = 'Mr';
        $data['first_name'] = 'Jean Pierre';
        $data['last_name'] = 'Barjac';
        $data['street'] = '47 rue du Groland';
        $data['postcode'] = '789500';
        $data['city'] = 'Bordeaux';
        $data['member_number'] = 'E-SD789';
        $data['subscription_year'] = '2024';
        $data['subscription_start_date'] = '01/01/2024';
        $data['subscription_end_date'] = '01/01/2025';
        $data['current_date'] = '12/01/2024';
        $data['subscription_fee'] = '70';
        $data['insurance_fee'] = '120';
        $data['item_reference'] = 'Standard';
        $data['payment_mode'] = 'Chèque';
        //$pdf = Pdf::loadView('pdf.membership.subscription-invoice', $data);
        $pdf = Pdf::loadView('pdf.membership.insurance-invoice', $data);
        $file = new UploadedFile(storage_path('app/public/subscription-invoice.pdf'), 'subscription-invoice.pdf');
        $document = new Document;
        $document->upload($file, 'invoice');
        $this->invoices()->save($document);
        //file_put_contents('debog_file.txt', print_r($file->getClientOriginalExtension(), true));
        //$pdf = Pdf::loadView('pdf.membership.test', $data);
        //return $pdf->stream();
        //$pdf->save(storage_path('app/public/subscription-invoice.pdf'));
        //return $pdf->download('invoice.pdf');
    }
}
