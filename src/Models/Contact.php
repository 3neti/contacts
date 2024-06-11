<?php

namespace Homeful\Contacts\Models;

use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Homeful\Contacts\Data\ContactData;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;

/**
 * Class Contact
 *
 * @property int    $id
 * @property string $reference_code
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $civil_status
 * @property string $sex
 * @property string $nationality
 * @property Carbon $date_of_birth
 * @property string $email
 * @property string $mobile
 * @property array  $spouse
 * @property array  $addresses
 * @property array  $employment
 * @property array  $co_borrowers
 * @property array  $uploads
 * @property array  $order
 * @property array  $media
 * @property Media  $idImage
 * @property Media  $selfieImage
 * @property Media  $payslipImage
 * @property Media  $voluntarySurrenderFormDocument
 * @property Media  $usufructAgreementDocument
 * @property Media  $contractToSellDocument
 * @property Media  $deedOfRestrictionsDocument
 * @property Media  $disclosureDocument
 * @property Media  $borrowerConformityDocument
 * @property Media  $statementOfAccountDocument
 * @property Media  $invoiceDocument
 * @property Media  $receiptDocument
 * @property Media  $deedOfSaleDocument
 *
 * @method int getKey()
 */
class Contact extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'reference_code',
        'first_name',
        'middle_name',
        'last_name',
        'civil_status',
        'sex',
        'nationality',
        'date_of_birth',
        'email',
        'mobile',
        'spouse',
        'addresses',
        'employment',
        'co_borrowers',
        'order',
        'idImage',
        'selfieImage',
        'payslipImage',
        'voluntarySurrenderFormDocument',
        'usufructAgreementDocument',
        'contractToSellDocument',
        'deedOfRestrictionsDocument',
        'disclosureDocument',
        'borrowerConformityDocument',
        'statementOfAccountDocument',
        'invoiceDocument',
        'receiptDocument',
        'deedOfSaleDocument',
    ];

    protected $casts = [
        'spouse' => 'array',
        'addresses' => 'array',
        'employment' => 'array',
        'co_borrowers' => 'array',
        'order' => 'array',
    ];

    protected array $dates = [
        'date_of_birth',
    ];

    public function toData(): array
    {
        return ContactData::fromModel($this)->toArray();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return parent::resolveRouteBinding($value, 'uid'); // TODO: Change the autogenerated stub
    }

    public function getIdImageAttribute(): ?Media
    {
        return $this->getFirstMedia('id-images');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setIdImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('idImage')
                ->toMediaCollection('id-images');
        }

        return $this;
    }

    public function getSelfieImageAttribute(): ?Media
    {
        return $this->getFirstMedia('selfie-images');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setSelfieImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('selfieImage')
                ->toMediaCollection('selfie-images');
        }

        return $this;
    }

    public function getPayslipImageAttribute(): ?Media
    {
        return $this->getFirstMedia('payslip-images');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setPayslipImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('payslipImage')
                ->toMediaCollection('payslip-images');
        }

        return $this;
    }

    public function getVoluntarySurrenderFormDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('voluntary_surrender_form-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setVoluntarySurrenderFormDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('voluntarySurrenderFormDocument')
                ->toMediaCollection('voluntary_surrender_form-documents');
        }

        return $this;
    }

    public function getUsufructAgreementDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('usufruct_agreement-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setUsufructAgreementDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('usufructAgreementDocument')
                ->toMediaCollection('usufruct_agreement-documents');
        }

        return $this;
    }

    public function getContractToSellDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('contract_to_sell-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setContractToSellDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('contractToSellDocument')
                ->toMediaCollection('contract_to_sell-documents');
        }

        return $this;
    }

    public function getDeedOfRestrictionsDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('deed_of_restrictions-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setDeedOfRestrictionsDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('deedOfRestrictionsDocument')
                ->toMediaCollection('deed_of_restrictions-documents');
        }

        return $this;
    }

    public function getDisclosureDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('disclosure-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setDisclosureDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('disclosureDocument')
                ->toMediaCollection('disclosure-documents');
        }

        return $this;
    }

    public function getBorrowerConformityDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('borrower_conformity-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setBorrowerConformityDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('borrowerConformityDocument')
                ->toMediaCollection('borrower_conformity-documents');
        }

        return $this;
    }

    public function getStatementOfAccountDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('statement_of_account-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setStatementOfAccountDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('statementOfAccountDocument')
                ->toMediaCollection('statement_of_account-documents');
        }

        return $this;
    }

    public function getInvoiceDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('invoice-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setInvoiceDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('invoiceDocument')
                ->toMediaCollection('invoice-documents');
        }

        return $this;
    }

    public function getReceiptDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('receipt-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setReceiptDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('receiptDocument')
                ->toMediaCollection('receipt-documents');
        }

        return $this;
    }

    public function getDeedOfSaleDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('deed_of_sale-documents');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setDeedOfSaleDocumentAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('deedOfSaleDocument')
                ->toMediaCollection('deed_of_sale-documents');
        }

        return $this;
    }

    public function registerMediaCollections(): void
    {
        $collections = [
            'id-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'selfie-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'payslip-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'voluntary_surrender_form-documents' => 'application/pdf',
            'usufruct_agreement-documents' => 'application/pdf',
            'contract_to_sell-documents' => 'application/pdf',
            'deed_of_restrictions-documents' => 'application/pdf',
            'disclosure-documents' => 'application/pdf',
            'borrower_conformity-documents' => 'application/pdf',
            'statement_of_account-documents' => 'application/pdf',
            'invoice-documents' => 'application/pdf',
            'receipt-documents' => 'application/pdf',
            'deed_of_sale-documents' => 'application/pdf',
        ];

        foreach ($collections as $collection => $mimeTypes) {
            $this->addMediaCollection($collection)
                ->singleFile()
                ->acceptsFile(function (File $file) use ($mimeTypes) {
                    return in_array(
                        needle: $file->mimeType,
                        haystack: (array) $mimeTypes
                    );
                });
        }
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getUploadsAttribute(): array
    {
        return collect($this->media)
            ->mapWithKeys(function ($item, $key) {
                $collection_name = $item['collection_name'];
                $name = Str::camel(Str::singular($collection_name));
                $url = $item['original_url'];

                return [
                    $key => [
                        'name' => $name,
                        'url' => $url,
                    ],
                ];
            })
            ->toArray();
    }

    /**
     * Helper function to get all media field names registered in the media collection i.e.,
     *
     * id-images => idImage
     * selfie-images => selfieImage
     * payslip-images => payslipImage
     * voluntary_surrender_form-documents => voluntarySurrenderFormDocument
     * usufruct_agreement-documents => usufructAgreementDocument
     * contract_to_sell-documents =< contractToSellDocument
     * deed_of_restrictions-documents => deedOfRestrictionsDocument
     * borrower_conformity-documents => borrowerConformityDocument
     * statement_of_account-documents => statementOfAccountDocument
     * invoice-documents => invoiceDocument
     * receipt-documents => receiptDocument
     * deed_of_sale-documents => deedOfSaleDocument
     */
    public function getMediaFieldNames(): array
    {
        return $this->getRegisteredMediaCollections()
            ->pluck('name')
            ->map(function ($key) {
                return Str::singular(Str::camel($key));
            })
            ->toArray();
    }
}
