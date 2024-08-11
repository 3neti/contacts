<?php

namespace Homeful\Contacts\Data;

use Homeful\Contacts\Models\Contact;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

class ContactData extends Data
{
    public function __construct(
        public string $reference_code, //buyers reference number or brn
        public PersonData $profile,
        public ?PersonData $spouse,
        /** @var AddressData[] */
        public DataCollection $addresses,
        /** @var ContactEmploymentData[] */
        public DataCollection $employment,
        /** @var PersonData[] */
        public DataCollection|Optional $co_borrowers,
        public ?ContactOrderData $order,
        /** @var UploadData[] */
        public DataCollection|Optional $uploads,

    ) {}

    //    public static function from(...$payloads): static
    //    {
    //        $attribs = (object) $payloads[0];
    //
    //        return new self(
    //            uid: $attribs->uid,
    //            profile: new PersonData(
    //                first_name: $attribs->first_name,
    //                middle_name: $attribs->middle_name,
    //                last_name: $attribs->last_name,
    //                civil_status: $attribs->civil_status,
    //                sex: $attribs->sex,
    //                nationality: $attribs->nationality,
    //                date_of_birth: $attribs->date_of_birth,
    //                email: $attribs->email,
    //                mobile: $attribs->mobile
    //            ),
    //            spouse: $attribs->spouse ? PersonData::from($attribs->spouse) : null,
    //            addresses: new DataCollection(AddressData::class, $attribs->addresses),
    //            employment: $attribs->employment ? ContactEmploymentData::from($attribs->employment) : null,
    //            co_borrowers: new DataCollection(PersonData::class, $attribs->co_borrowers),
    //            order: $attribs->order ? ContactOrderData::from($attribs->order) : null,
    //            uploads: new DataCollection(
    //                dataClass: UploadData::class,
    //                items: collect($attribs->media)
    //                    ->mapWithKeys(function ($item, $key) {
    //                        return [
    //                            $key => [
    //                                'name' => $item['name'],
    //                                'url' => $item['original_url']
    //                            ]
    //                        ];
    //                    })
    //                    ->toArray()
    //            ),
    //            reference_code: $attribs->reference_code ?: null
    //        );
    //    }

    public static function fromModel(Contact $model): self
    {

        $order = $model->order;

        $order['baf_date'] = isset($order['baf_date']) && $order['baf_date'] !== null
            ? date('Y-m-d', strtotime($order['baf_date']))
            : '';
        $order['date_created'] = isset($order['date_created']) && $order['date_created'] !== null
            ? date('Y-m-d', strtotime($order['date_created']))
            : '';

        $order['payment_scheme'] = new PaymentSchemeData(
            scheme: isset($order['payment_scheme']['scheme']) && $order['payment_scheme']['scheme'] !== null ? $order['payment_scheme']['scheme'] : null,
            method: isset($order['payment_scheme']['method']) && $order['payment_scheme']['method'] !== null ? $order['payment_scheme']['method'] : null,
            collectible_price: isset($order['payment_scheme']['collectible_price']) && $order['payment_scheme']['collectible_price'] !== null ? $order['payment_scheme']['collectible_price'] : null,
            commissionable_amount: isset($order['payment_scheme']['commissionable_amount']) && $order['payment_scheme']['commissionable_amount'] !== null ? $order['payment_scheme']['commissionable_amount'] : null,
            evat_percentage: isset($order['payment_scheme']['evat_percentage']) && $order['payment_scheme']['evat_percentage'] !== null ? $order['payment_scheme']['evat_percentage'] : null,
            evat_amount: isset($order['payment_scheme']['evat_amount']) && $order['payment_scheme']['evat_amount'] !== null ? $order['payment_scheme']['evat_amount'] : null,
            net_total_contact_price: isset($order['payment_scheme']['net_total_contract_price']) && $order['payment_scheme']['net_total_contract_price'] !== null ? $order['payment_scheme']['net_total_contract_price'] : null,
            total_contact_price: isset($order['payment_scheme']['total_contract_price']) && $order['payment_scheme']['total_contract_price'] !== null ? $order['payment_scheme']['total_contract_price'] : null,
            payments: isset($order['payment_scheme']['payments']) && $order['payment_scheme']['payments'] !== null
                ? new DataCollection(PaymentData::class, $order['payment_scheme']['payments']) : null,
            fees: isset($order['payment_scheme']['fees']) && $order['payment_scheme']['fees'] !== null
                ? new DataCollection(PaymentData::class, $order['payment_scheme']['fees']) : null,
            payment_remarks: isset($order['payment_scheme']['payment_remarks']) && $order['payment_scheme']['payment_remarks'] !== null ? $order['payment_scheme']['payment_remarks'] : null,
            transaction_remarks: isset($order['payment_scheme']['transaction_remarks']) && $order['payment_scheme']['transaction_remarks'] !== null ? $order['payment_scheme']['transaction_remarks'] : null,
            discount_rate: isset($order['payment_scheme']['discount_rate']) && $order['payment_scheme']['discount_rate'] !== null ? $order['payment_scheme']['discount_rate'] : null,
            conditional_discount: isset($order['payment_scheme']['conditional_discount']) && $order['payment_scheme']['conditional_discount'] !== null ? $order['payment_scheme']['conditional_discount'] : null,
            transaction_sub_status: isset($order['payment_scheme']['transaction_sub_status']) && $order['payment_scheme']['transaction_sub_status'] !== null ? $order['payment_scheme']['transaction_sub_status'] : null,
        );

        return new self(
            reference_code: $model->reference_code,
            profile: new PersonData(
                first_name: $model->first_name,
                middle_name: $model->middle_name,
                last_name: $model->last_name,
                name_suffix: $model->name_suffix,
                civil_status: $model->civil_status,
                sex: $model->sex,
                nationality: $model->nationality,
                date_of_birth: $model->date_of_birth->format('Y-m-d'),
                email: $model->email,
                mobile: $model->mobile->formatNational(),
                other_mobile: $model->other_mobile,
                help_number: $model->help_number,
                landline: $model->landline,
                mothers_maiden_name: $model->mothers_maiden_name,
            ),
            spouse: $model->spouse ? PersonData::from($model->spouse) : null,
            addresses: new DataCollection(AddressData::class, $model->addresses),
            employment: new DataCollection(ContactEmploymentData::class, $model->employement),
            co_borrowers: new DataCollection(PersonData::class, $model->co_borrowers),
            order: $model->order ? ContactOrderData::from($order) : null,
            uploads: new DataCollection(UploadData::class, $model->uploads),
        );
    }

    public function toArray(): array
    {
        $array = [
            'reference_code' => $this->reference_code,
            'profile' => $this->profile->toArray(),
            'spouse' => $this->spouse->toArray(),
            'addresses' => $this->addresses->toArray(),
            'employment' => $this->employment->toArray(),
            'co_borrowers' => $this->co_borrowers->toArray(),
            'order' => [
                'sku' => $this->order->sku,
                'seller_commission_code' => $this->order->seller_commission_code,
                'property_code' => $this->order->property_code,
                // for GNC
                'company_name' => $this->order->company_name,
                'project_name' => $this->order->project_name,
                'project_code' => $this->order->project_code,
                'property_name' => $this->order->property_name,
                'phase' => $this->order->phase,
                'block' => $this->order->block,
                'lot' => $this->order->lot,
                'lot_area' => $this->order->lot_area,
                'floor_area' => $this->order->floor_area,
                'loan_term' => $this->order->loan_term,
                'loan_interest_rate' => $this->order->loan_interest_rate,
                'tct_no' => $this->order->tct_no,
                'project_location' => $this->order->project_location,
                'project_address' => $this->order->project_address,
                'mrif_fee' => $this->order->mrif_fee,
                'reservation_rate' => $this->order->reservation_rate,

                // for GNC
                'property_type' => $this->order->property_type,
                'os_status' => $this->order->os_status,
                'class_field' => $this->order->class_field,
                'segment_field' => $this->order->segment_field,
                'rebooked_id_form' => $this->order->rebooked_id_form,
                'buyer_action_form_number' => $this->order->buyer_action_form_number, // buyer_action_form
                'buyer_action_form_date' => $this->order->buyer_action_form_date,

                'cancellation_type' => $this->order->cancellation_type,
                'cancellation_reason' => $this->order->cancellation_reason,
                'cancellation_remarks' => $this->order->cancellation_remarks,

                'unit_type' => $this->order->unit_type,
                'unit_type_interior' => $this->order->unit_type_interior,
                'house_color' => $this->order->house_color,
                'construction_status' => $this->order->construction_status,
                'transaction_reference' => $this->order->transaction_reference,
                'reservation_date' => $this->order->reservation_date,
                'circular_number' => $this->order->circular_number,

                // out of place fields
                'date_created' => $this->order->date_created,
                'ra_date' => $this->order->ra_date,
                'date_approved' => $this->order->date_approved,
                'date_expiration' => $this->order->date_expiration,
                'os_month' => $this->order->os_month,
                'due_date' => $this->order->due_date,
                'total_payments_made' => $this->order->total_payments_made,
                'transaction_status' => $this->order->transaction_status,
                'staging_status' => $this->order->staging_status,
                'period_id' => $this->order->period_id, // PeriodID (RE, DP, BP, MF, Fully Paid)
                'date_closed' => $this->order->date_closed,
                'closed_reason' => $this->order->closed_reason,
                'date_cancellation' => $this->order->date_cancellation,
                'baf_number' => $this->order->baf_number,
                'baf_date' => $this->order->baf_date,
                'client_id_buyer' => $this->order->client_id_buyer,
                'buyer_age' => $this->order->buyer_age,
                'client_id_spouse' => $this->order->client_id_spouse,
                'payment_scheme' => $this->order->payment_scheme == null ? null : $this->order->payment_scheme->toArray(),
                'seller_data' => $this->order->seller_data == null ? null : $this->order->seller_data->toArray(),
            ],
            'uploads' => $this->uploads->toArray(),
        ];


        return $array;
    }
}

class ContactOrderData extends Data
{
    public function __construct(
        public string $sku,
        public string $seller_commission_code,
        public string $property_code,
        //for GNC
        public ?string $company_name,
        public ?string $project_name,
        public ?string $project_code,
        public ?string $property_name,
        public ?string $phase,
        public ?string $block,
        public ?string $lot,
        public ?string $lot_area,
        public ?string $floor_area,
        public ?string $loan_term,
        public ?string $loan_interest_rate,
        public ?string $tct_no,
        public ?string $project_location,
        public ?string $project_address,
        public ?string $mrif_fee,
        public ?string $reservation_rate,
        //for GNC
        public ?string $property_type,
        public ?string $os_status,
        public ?string $class_field,
        public ?string $segment_field,
        public ?string $rebooked_id_form,
        public ?string $buyer_action_form_number, //buyer_action_form
        public ?string $buyer_action_form_date,

        public ?string $cancellation_type,
        public ?string $cancellation_reason,
        public ?string $cancellation_remarks,

        public ?string $unit_type,
        public ?string $unit_type_interior,
        public ?string $house_color,
        public ?string $construction_status,
        public ?string $transaction_reference,
        public ?string $reservation_date,
        public ?string $circular_number,

        //out of place fields
        public ?string $date_created,
        public ?string $ra_date,
        public ?string $date_approved,
        public ?string $date_expiration,
        public ?string $os_month,
        public ?string $due_date,
        public ?string $total_payments_made,
        public ?string $transaction_status,
        public ?string $staging_status,
        public ?string $period_id, //PeriodID (RE,DP,BP,MF,Fully Paid)
        public ?string $date_closed,
        public ?string $closed_reason,
        public ?string $date_cancellation,
        public ?string $baf_number,
        public ?string $baf_date,
        public ?string $client_id_buyer,
        public ?string $buyer_age,
        public ?string $client_id_spouse,

        public PaymentSchemeData|null $payment_scheme,
        public ?SellerData $seller_data,

    ) {

    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'seller_commission_code' => $this->seller_commission_code,
            'property_code' => $this->property_code,
            'company_name' => $this->company_name,
            'project_name' => $this->project_name,
            'project_code' => $this->project_code,
            'property_name' => $this->property_name,
            'phase' => $this->phase,
            'block' => $this->block,
            'lot' => $this->lot,
            'lot_area' => $this->lot_area,
            'floor_area' => $this->floor_area,
            'loan_term' => $this->loan_term,
            'loan_interest_rate' => $this->loan_interest_rate,
            'tct_no' => $this->tct_no,
            'project_location' => $this->project_location,
            'project_address' => $this->project_address,
            'mrif_fee' => $this->mrif_fee,
            'reservation_rate' => $this->reservation_rate,
            'property_type' => $this->property_type,
            'os_status' => $this->os_status,
            'class_field' => $this->class_field,
            'segment_field' => $this->segment_field,
            'rebooked_id_form' => $this->rebooked_id_form,
            'buyer_action_form_number' => $this->buyer_action_form_number,
            'buyer_action_form_date' => $this->buyer_action_form_date,
            'cancellation_type' => $this->cancellation_type,
            'cancellation_reason' => $this->cancellation_reason,
            'cancellation_remarks' => $this->cancellation_remarks,
            'unit_type' => $this->unit_type,
            'unit_type_interior' => $this->unit_type_interior,
            'house_color' => $this->house_color,
            'construction_status' => $this->construction_status,
            'transaction_reference' => $this->transaction_reference,
            'reservation_date' => $this->reservation_date,
            'circular_number' => $this->circular_number,
            'date_created' => $this->date_created,
            'ra_date' => $this->ra_date,
            'date_approved' => $this->date_approved,
            'date_expiration' => $this->date_expiration,
            'os_month' => $this->os_month,
            'due_date' => $this->due_date,
            'total_payments_made' => $this->total_payments_made,
            'transaction_status' => $this->transaction_status,
            'staging_status' => $this->staging_status,
            'period_id' => $this->period_id,
            'date_closed' => $this->date_closed,
            'closed_reason' => $this->closed_reason,
            'date_cancellation' => $this->date_cancellation,
            'baf_number' => $this->baf_number,
            'baf_date' => $this->baf_date,
            'client_id_buyer' => $this->client_id_buyer,
            'buyer_age' => $this->buyer_age,
            'client_id_spouse' => $this->client_id_spouse,
            'payment_scheme' => $this->payment_scheme ? $this->payment_scheme->toArray() : null,
            'seller_data' => $this->seller_data ? $this->seller_data->toArray() : null,
        ];
    }
}

class ContactEmploymentData extends Data
{
    public function __construct(
        public string $employment_status,
        public string $monthly_gross_income,
        public string $current_position,
        public string $employment_type,
        public ContactEmploymentEmployerData $employer,
        public ContactEmploymentIdData|Optional $id,
        //for GNC
        public ?string $years_in_service,
        public ?string $salary_range,
        public ?string $industry,
        public ?string $department_name,
        public ?string $type, //spouse, coborrower, buyer
    ) {}
}

class ContactEmploymentEmployerData extends Data
{
    public function __construct(
        public string $name,
        public string $industry,
        public string $nationality,
        public AddressData $address,
        public string $contact_no,
        //for GNC
        public ?string $employer_status,
        public ?string $type,
        public ?string $status,
        public ?string $year_established,
        public ?string $total_number_of_employees,
        public ?string $email,

    ) {}
}

class ContactEmploymentIdData extends Data
{
    public function __construct(
        public ?string $tin,
        public ?string $pagibig,
        public ?string $sss,
        public ?string $gsis,
    ) {}
}

class UploadData extends Data
{
    public function __construct(
        public string $name,
        public string $url
    ) {}
}

class SellerData
{
    public function __construct(
        public ?string $name,
        public ?string $id,
        public ?string $superior,
        public ?string $team_head,
        public ?string $chief_seller_officer,
        public ?string $deputy_chief_seller_officer,
        public ?string $type,
        public ?string $reference_no, //seller id
        public ?string $unit //seller id
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'superior' => $this->superior,
            'team_head' => $this->team_head,
            'chief_seller_officer' => $this->chief_seller_officer,
            'deputy_chief_seller_officer' => $this->deputy_chief_seller_officer,
            'type' => $this->type,
            'reference_no' => $this->reference_no,
            'unit' => $this->unit,
        ];
    }
}

class PaymentSchemeData
{
    public function __construct(
        public ?string $scheme,
        public ?string $method,
        public ?string $collectible_price,
        public ?string $commissionable_amount,
        public ?string $evat_percentage,
        public ?string $evat_amount,
        public ?string $net_total_contact_price,
        public ?string $total_contact_price,
        /** @var PaymentData[] */
        public ?DataCollection $payments,
        /** @var FeesData[] */
        public ?DataCollection $fees,
        public ?string $payment_remarks,
        public ?string $transaction_remarks,
        public ?string $discount_rate,
        public ?string $conditional_discount,
        public ?string $transaction_sub_status,

    ) {

    }

    public function toArray(): array
    {
        return [
            'scheme' => $this->scheme,
            'method' => $this->method,
            'collectible_price' => $this->collectible_price,
            'commissionable_amount' => $this->commissionable_amount,
            'evat_percentage' => $this->evat_percentage,
            'evat_amount' => $this->evat_amount,
            'net_total_contact_price' => $this->net_total_contact_price,
            'total_contact_price' => $this->total_contact_price,
            'payments' => $this->payments ? $this->payments->toArray() : null,
            'fees' => $this->fees ? $this->fees->toArray() : null,
            'payment_remarks' => $this->payment_remarks,
            'transaction_remarks' => $this->transaction_remarks,
            'discount_rate' => $this->discount_rate,
            'conditional_discount' => $this->conditional_discount,
            'transaction_sub_status' => $this->transaction_sub_status,
        ];
    }
}

class PaymentData extends Data
{
    public function __construct(
        public ?string $type, //processing_fee, home_utility_connection_fee, equity, balance
        public ?string $amount_paid,
        public ?string $date,
        public ?string $reference_number,
    ) {}
}

class FeesData extends Data
{
    public function __construct(
        public ?string $name, //processing fee, home_utility_connection_fee, mrif, rental,
        public ?string $amount,
    ) {}
}
