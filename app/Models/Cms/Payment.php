<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use App\Models\Cms\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Delete the model from the database (override).
     *
     * @return bool|null
     *
     * @throws \LogicException
     */
    public function delete()
    {
        foreach ($this->invoices as $invoice) {
            $invoice->delete();
        }

        parent::delete();
    }

    /*
     * Creates a pdf invoice file.
     */
    public function createInvoice(string $view, array $data, string $fileName)
    {
        // Create a tmp directory in case it doesn't exists.
        Storage::makeDirectory('tmp');
        $pdf = Pdf::loadView($view, $data);
        // Store temporarily the invoice file. 
        $pdf->save(storage_path('app/tmp/'.$fileName));
        // Create a object from the temporary file.
        $file = new UploadedFile(storage_path('app/tmp/'.$fileName), $fileName);
        // Store the invoice file as a Document object
        $document = new Document;
        $document->upload($file, 'invoice');
        $this->invoices()->save($document);
        // Store the pdf invoice file on the disk.
        $pdf->save(storage_path('app/public/'.$document->disk_name));
        // Finally delete the temporary file.
        Storage::delete(storage_path('app/tmp/'.$fileName));
    }
}
