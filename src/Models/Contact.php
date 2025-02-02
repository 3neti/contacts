<?php

namespace Homeful\Contacts\Models;

use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Homeful\Common\Interfaces\BorrowerInterface;
use Spatie\MediaLibrary\MediaCollections\File;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Homeful\Contacts\Data\ContactData;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Whitecube\Price\Price;
use Brick\Money\Money;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Contact
 *
 * @property string $id
 * @property string $reference_code
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $name_suffix
 * @property string $civil_status
 * @property string $sex
 * @property string $nationality
 * @property Carbon $date_of_birth
 * @property string $email
 * @property PhoneNumber $mobile
 * @property PhoneNumber $other_mobile
 * @property PhoneNumber $help_number
 * @property string $landline
 * @property string $mothers_maiden_name
 * @property array $spouse
 * @property array $addresses
 * @property array $employment
 * @property array $co_borrowers
 * @property array $uploads
 * @property array $order
 * @property array $media
 * @property Media $idImage
 * @property Media $selfieImage
 * @property Media $payslipImage
 * @property Media $signatureImage
 * @property Media $voluntarySurrenderFormDocument
 * @property Media $usufructAgreementDocument
 * @property Media $contractToSellDocument
 * @property Media $deedOfRestrictionsDocument
 * @property Media $disclosureDocument
 * @property Media $borrowerConformityDocument
 * @property Media $statementOfAccountDocument
 * @property Media $invoiceDocument
 * @property Media $receiptDocument
 * @property Media $deedOfSaleDocument
 * @property array $current_status
 * @property array $current_status_code
 *
 * @method int getKey()
 */
class Contact extends Authenticatable implements BorrowerInterface, HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Notifiable;

    protected $fillable = [
        'reference_code',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'civil_status',
        'sex',
        'nationality',
        'date_of_birth',
        'email',
        'mobile',
        'other_mobile',
        'help_number',
        'landline',
        'mothers_maiden_name',
        'spouse',
        'addresses',
        'employment',
        'co_borrowers',
        'order',
        'idImage',
        'selfieImage',
        'payslipImage',
        'signatureImage',
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
        'current_status',
        'current_status_code',
    ];

    protected $casts = [
        // 'mobile' => RawPhoneNumberCast::class.':PH',
        // 'other_mobile' => RawPhoneNumberCast::class.':PH',
        // 'help_number' => RawPhoneNumberCast::class.':PH',
        'spouse' => 'array',
        'addresses' => 'array',
        'employment' => 'array',
        'co_borrowers' => 'array',
        'order' => 'array',

    ];

    protected array $dates = [
        'date_of_birth',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function booted(): void
    {
        static::creating(function (Contact $contact) {
            $contact->id = Str::uuid()->toString();
            $contact->setAttribute('password', Str::password());
        });
    }

    public function routeNotificationForEngageSpark(): string
    {
        return $this->mobile;
    }

    public function getContactId(): string
    {
        return (string) $this->id;
    }

    public function toData(): array
    {
        return ContactData::fromModel($this)->toArray();
    }

//    public function resolveRouteBinding($value, $field = null)
//    {
//        return parent::resolveRouteBinding($value, 'uid'); // TODO: Change the autogenerated stub
//    }

    public function getNameAttribute(): string
    {

        return $this->name?? "$this->first_name $this->middle_name $this->last_name";
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

    public function getSignatureImageAttribute(): ?Media
    {
        return $this->getFirstMedia('signature-image');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setSignatureImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('signatureImage')
                ->toMediaCollection('signature-image');
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

    //    protected function casts(): array
    //    {
    //        return [
    //            'date_of_birth' => 'datetime:Y-m-d',
    //        ];
    //    }

    public function getBirthdate(): Carbon
    {
        return new Carbon($this->date_of_birth);
    }

    public function getWages(): Money|float
    {
        $buyerEmployment = collect($this->employment)->firstWhere('type', 'buyer');

        return $buyerEmployment
            ? Money::of(Arr::get($buyerEmployment, 'monthly_gross_income', 0), 'PHP')
            : Money::of(0, 'PHP');
    }

    public function getRegional(): bool
    {
        $region = Arr::get($this->addresses, '0.administrative_area', 'NCR');

        return ! ($region == 'NCR' || $region == 'Metro Manila');
    }

    public function getMobile(): PhoneNumber
    {
        return new PhoneNumber($this->mobile, 'PH');
    }

    public function setMobile($value): void
    {
        if ($value instanceof PhoneNumber) {
            $this->attributes['mobile'] = $value->formatE164();
        } elseif (is_string($value)) {
            $this->attributes['mobile'] = $value;
        } else {
            throw new \InvalidArgumentException('Mobile must be a string or an instance of PhoneNumber.');
        }
    }

    protected function DateOfBirth(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => new Carbon($value),
            set: fn (mixed $value) => $value instanceof Carbon ? $value->format('Y-m-d') : $value
        );
    }

    public function getSellerCommissionCode(): string
    {
        return $this->getAttribute('order')->get('seller_commission_code', 'N/A');
    }

    public function getGrossMonthlyIncome(): Price
    {
        return new Price($this->getWages());
    }
}
