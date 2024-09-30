<?php

namespace Homeful\Contacts\Actions;

use Homeful\Contacts\Events\ContactPersisted;
use Homeful\Contacts\Models\Contact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class PersistContactAction
{
    use AsAction;

    protected function persist(array $validated): Contact
    {
        return tap(new Contact($validated), function ($contact) use ($validated) {
            $contact = Contact::updateOrCreate(
                ['reference_code' => $validated['reference_code']], // Unique identifier, adjust as needed
                $validated
            );
            ContactPersisted::dispatch($contact);
        });
    }

    public function handle(array $attribs): Contact
    {
        $validator = Validator::make($attribs, $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $validated = $validator->validated();

        return $this->persist($validated);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            //            'reference_code' => ['nullable', 'string'],
            //
            //            'first_name' => ['required', 'string'],
            //            'middle_name' => ['required', 'string'],
            //            'last_name' => ['required', 'string'],
            //            'civil_status' => ['required', 'string'],
            //            'sex' => ['required', 'string'],
            //            'nationality' => ['required', 'string'],
            //            'date_of_birth' => ['required', 'date'],
            //            'email' => ['required', 'string'],
            //            'mobile' => ['required', 'string'],
            //            'other_mobile' => ['nullable', 'string'],
            //            'landline' => ['nullable', 'string'],
            //            'help_number' => ['nullable', 'string'],
            //            'mothers_maiden_name' => ['nullable', 'string'],
            //
            //            'addresses' => ['required', 'array'],
            //            'addresses.*.type' => ['required', 'string'],
            //            'addresses.*.ownership' => ['required', 'string'],
            //            'addresses.*.full_address' => ['nullable', 'string'],
            //            'addresses.*.address1' => ['nullable', 'string'], //improve this, required if full address
            //            'addresses.*.address2' => ['nullable', 'string'],
            //            'addresses.*.sublocality' => ['nullable', 'string'],
            //            'addresses.*.locality' => ['nullable', 'string'], //improve this, required if full address
            //            'addresses.*.administrative_area' => ['nullable', 'string'],
            //            'addresses.*.postal_code' => ['nullable', 'string'],
            //            'addresses.*.sorting_code' => ['nullable', 'string'],
            //            'addresses.*.country' => ['required', 'string'],
            //            'addresses.*.block' => ['nullable', 'string'],
            //            'addresses.*.lot' => ['nullable', 'string'],
            //            'addresses.*.unit' => ['nullable', 'string'],
            //            'addresses.*.floor' => ['nullable', 'string'],
            //            'addresses.*.street' => ['nullable', 'string'],
            //            'addresses.*.building' => ['nullable', 'string'],
            //            'addresses.*.length_of_stay' => ['nullable', 'string'],
            //
            //            'spouse' => ['nullable', 'array'],
            //            'spouse.first_name' => ['required_with:spouse', 'string'],
            //            'spouse.middle_name' => ['required_with:spouse', 'string'],
            //            'spouse.last_name' => ['required_with:spouse', 'string'],
            //            'spouse.civil_status' => ['required_with:spouse', 'string'],
            //            'spouse.sex' => ['required_with:spouse', 'string'],
            //            'spouse.nationality' => ['required_with:spouse', 'string'],
            //            'spouse.date_of_birth' => ['required_with:spouse', 'string'],
            //            'spouse.email' => ['required_with:spouse', 'string'],
            //            'spouse.mobile' => ['required_with:spouse', 'string'],
            //            'spouse.other_mobile' => ['nullable', 'string'],
            //            'spouse.help_number' => ['nullable', 'string'],
            //            'spouse.mothers_maiden_name' => ['nullable', 'string'],
            //
            //            'employment' => ['nullable', 'array'],
            //            'employment.*.employment_status' => ['required_with:employment', 'string'],
            //            'employment.*.monthly_gross_income' => ['required_with:employment', 'string'],
            //            'employment.*.current_position' => ['required_with:employment', 'string'],
            //            'employment.*.employment_type' => ['required_with:employment', 'string'],
            //            'employment.*.employer' => ['required_with:employment', 'array'],
            //            'employment.*.employer.name' => ['required_with:employment.employer', 'string'],
            //            'employment.*.employer.industry' => ['required_with:employment.employer', 'string'],
            //            'employment.*.employer.nationality' => ['required_with:employment.employer', 'string'],
            //            'employment.*.employer.address' => ['required_with:employment.employer', 'array'],
            //            'employment.*.employer.address.type' => ['required_with:employment.employer.address', 'string'],
            //            'employment.*.employer.address.ownership' => ['required_with:employment.employer.address', 'string'],
            //            'employment.*.employer.address.address1' => ['required_with:employment.employer.address', 'string'],
            //            'employment.*.employer.address.locality' => ['required_with:employment.employer.address', 'string'],
            //            'employment.*.employer.address.postal_code' => ['required_with:employment.employer.address', 'string'],
            //            'employment.*.employer.address.country' => ['required_with:employment.employer.address', 'string'],
            //            'employment.*.employer.contact_no' => ['required_with:employment.employer', 'string'],
            //            'employment.*.id' => ['required', 'array'],
            //            'employment.*.id.tin' => ['required_without_all:employment.id.pagibig,employment.id.sss,employment.id.gsis', 'string'],
            //            'employment.*.id.pagibig' => ['required_without_all:employment.id.tin,employment.id.sss,employment.id.gsis', 'string'],
            //            'employment.*.id.sss' => ['required_without_all:employment.id.tin,employment.id.pagibig,employment.id.gsis', 'string'],
            //            'employment.*.id.gsis' => ['required_without_all:employment.id.tin,employment.id.pagibig,employment.id.sss', 'string'],
            //
            //            'co_borrowers' => ['nullable', 'array'],
            //            'co_borrowers.*.first_name' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.middle_name' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.last_name' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.civil_status' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.sex' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.nationality' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.date_of_birth' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.email' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.mobile' => ['required_with:co_borrowers', 'string'],
            //            'co_borrowers.*.other_mobile' => ['nullable', 'string'],
            //            'co_borrowers.*.help_number' => ['nullable', 'string'],
            //            'co_borrowers.*.mothers_maiden_name' => ['nullable', 'string'],
            //
            //            'order' => ['nullable', 'array'],
            //            'order.sku' => ['nullable', 'string'],
            //            'order.seller_commission_code' => ['nullable', 'string'],
            //            'order.property_code' => ['nullable', 'string'],
            //
            //            'order.company_name' => ['nullable', 'string'],
            //            'order.project_name' => ['nullable', 'string'],
            //            'order.project_code' => ['nullable', 'string'],
            //            'order.property_name' => ['nullable', 'string'],
            //            'order.phase' => ['nullable', 'string'],
            //            'order.block' => ['nullable', 'numeric'],
            //            'order.lot' => ['nullable', 'numeric'],
            //            'order.lot_area' => ['nullable', 'numeric'],
            //            'order.floor_area' => ['nullable', 'numeric'],
            //            'order.tcp' => ['nullable', 'numeric'],
            //            'order.loan_term' => ['nullable', 'numeric'],
            //            'order.loan_interest_rate' => ['nullable', 'numeric'],
            //            'order.tct_no' => ['nullable', 'string'],
            //            'order.project_location' => ['nullable', 'string'],
            //            'order.project_address' => ['nullable', 'string'],
            //            'order.mrif_fee' => ['nullable', 'numeric'],
            //            'order.reservation_rate' => ['nullable', 'numeric'],
            //
            //            'order.unit_type' => ['nullable', 'string'],
            //            'order.unit_type_interior' => ['nullable', 'string'],
            //            'order.house_color' => ['nullable', 'string'],
            //            'order.construction_status' => ['nullable', 'string'],
            //            'order.transaction_reference' => ['nullable', 'string'],
            //            'order.reservation_date' => ['nullable', 'string'],
            //            'order.circular_number' => ['nullable', 'string'],
            //            'order.date_created' => ['nullable', 'string'],
            //            'order.ra_date' => ['nullable', 'string'],
            //            'order.date_approved' => ['nullable', 'string'],
            //            'order.date_expiration' => ['nullable', 'string'],
            //            'order.os_month' => ['nullable', 'string'],
            //            'order.due_date' => ['nullable', 'string'],
            //            'order.total_payments_made' => ['nullable', 'string'],
            //            'order.transaction_status' => ['nullable', 'string'],
            //            'order.staging_status' => ['nullable', 'string'],
            //            'order.period_id' => ['nullable', 'string'],
            //            'order.date_closed' => ['nullable', 'string'],
            //            'order.closed_reason' => ['nullable', 'string'],
            //            'order.date_cancellation' => ['nullable', 'string'],
            //
            //            'order.payments' => ['nullable', 'array'],
            //            'order.payments.*.type' => ['nullable', 'string'],
            //            'order.payments.*.amount' => ['nullable', 'string'],
            //            'order.payments.*.payment_date' => ['nullable', 'string'],
            //
            //            'order.fees.*.name' => ['nullable', 'string'],
            //            'order.fees.*.amount' => ['nullable', 'string'],

            'reference_code' => ['nullable', 'string'],

            'first_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'name_suffix' => ['nullable', 'string'],
            'civil_status' => ['required', 'string'],
            'sex' => ['required', 'string'],
            'nationality' => ['required', 'string'],
            'date_of_birth' => ['required'],
            'email' => ['required', 'string'],
            'mobile' => ['required', 'string'],
            'other_mobile' => ['nullable', 'string'],
            'landline' => ['nullable', 'string'],
            'help_number' => ['nullable', 'string'],
            'mothers_maiden_name' => ['nullable', 'string'],

            'addresses' => ['required', 'array'],
            'addresses.*.type' => ['required', 'string'],
            'addresses.*.ownership' => ['nullable', 'string'],
            'addresses.*.full_address' => ['nullable', 'string'],
            'addresses.*.address1' => ['nullable', 'string'], //improve this, required if full address
            'addresses.*.address2' => ['nullable', 'string'],
            'addresses.*.sublocality' => ['nullable', 'string'],
            'addresses.*.locality' => ['nullable', 'string'], //improve this, required if full address
            'addresses.*.administrative_area' => ['nullable', 'string'],
            'addresses.*.postal_code' => ['nullable', 'string'],
            'addresses.*.sorting_code' => ['nullable', 'string'],
            'addresses.*.country' => ['required', 'string'],
            'addresses.*.block' => ['nullable', 'string'],
            'addresses.*.lot' => ['nullable', 'string'],
            'addresses.*.unit' => ['nullable', 'string'],
            'addresses.*.floor' => ['nullable', 'string'],
            'addresses.*.street' => ['nullable', 'string'],
            'addresses.*.building' => ['nullable', 'string'],
            'addresses.*.length_of_stay' => ['nullable', 'string'],
            'addresses.*.region' => ['nullable', 'string'],

            'spouse' => ['nullable', 'array'],
            'spouse.first_name' => ['nullable', 'string'],
            'spouse.middle_name' => ['nullable', 'string'],
            'spouse.last_name' => ['nullable', 'string'],
            'spouse.name_suffix' => ['nullable', 'string'],
            'spouse.mothers_maiden_name' => ['nullable', 'string'],
            'spouse.civil_status' => ['nullable', 'string'],
            'spouse.sex' => ['nullable', 'string'],
            'spouse.nationality' => ['nullable', 'string'],
            'spouse.date_of_birth' => ['nullable'],
            'spouse.email' => ['nullable', 'string'],
            'spouse.mobile' => ['nullable', 'string'],
            'spouse.other_mobile' => ['nullable', 'string'],
            'spouse.help_number' => ['nullable', 'string'],
            'spouse.client_id' => ['nullable'],
            'spouse.landline' => ['nullable'],
            'spouse.age' => ['nullable'],

            'employment' => ['nullable', 'array'],
            'employment.*.type' => ['nullable', 'string'],
            'employment.*.employment_status' => ['nullable', 'string'],
            'employment.*.monthly_gross_income' => ['nullable', 'numeric'],
            'employment.*.current_position' => ['nullable', 'string'],
            'employment.*.rank' => ['nullable', 'string'],
            'employment.*.employment_type' => ['nullable', 'string'],
            'employment.*.years_in_service' => ['nullable', 'string'],
            'employment.*.salary_range' => ['nullable', 'string'],
            'employment.*.department_name' => ['nullable', 'string'],
            'employment.*.character_reference' => ['nullable', 'array'],
            'employment.*.character_reference.name' => ['nullable', 'string'],
            'employment.*.character_reference.mobile' => ['nullable', 'string'],

            'employment.*.employer' => ['nullable', 'array'],
            'employment.*.employer.name' => ['nullable', 'string'],
            'employment.*.employer.industry' => ['nullable', 'string'],
            'employment.*.employer.type' => ['nullable', 'string'],
            'employment.*.employer.status' => ['nullable', 'string'],
            'employment.*.employer.year_established' => ['nullable', 'string'],
            'employment.*.employer.total_number_of_employees' => ['nullable'],
            'employment.*.employer.email' => ['nullable', 'string'],
            'employment.*.employer.nationality' => ['nullable', 'string'],
            'employment.*.employer.contact_no' => ['nullable', 'string'],
            'employment.*.employer.fax' => ['nullable', 'string'],

            'employment.*.employer.address' => ['nullable', 'array'],
            'employment.*.employer.address.type' => ['nullable', 'string'],
            'employment.*.employer.address.ownership' => ['nullable', 'string'],
            'employment.*.employer.address.full_address' => ['nullable', 'string'],
            'employment.*.employer.address.address1' => ['nullable', 'string'],
            'employment.*.employer.address.address2' => ['nullable', 'string'],
            'employment.*.employer.address.sublocality' => ['nullable', 'string'],
            'employment.*.employer.address.locality' => ['nullable', 'string'],
            'employment.*.employer.address.administrative_area' => ['nullable', 'string'],
            'employment.*.employer.address.postal_code' => ['nullable', 'string'],
            'employment.*.employer.address.sorting_code' => ['nullable', 'string'],
            'employment.*.employer.address.country' => ['nullable', 'string'],
            'employment.*.employer.address.block' => ['nullable', 'string'],
            'employment.*.employer.address.lot' => ['nullable', 'string'],
            'employment.*.employer.address.unit' => ['nullable', 'string'],
            'employment.*.employer.address.floor' => ['nullable', 'string'],
            'employment.*.employer.address.street' => ['nullable', 'string'],
            'employment.*.employer.address.building' => ['nullable', 'string'],
            'employment.*.employer.address.length_of_stay' => ['nullable', 'string'],

            'employment.*.id' => ['required', 'array'],
            'employment.*.id.tin' => ['nullable', 'string'],
            'employment.*.id.pagibig' => ['nullable', 'string'],
            'employment.*.id.sss' => ['nullable', 'string'],
            'employment.*.id.gsis' => ['nullable', 'string'],

            'co_borrowers' => ['nullable', 'array'],
            'co_borrowers.*.name' => ['nullable', 'string'],
            'co_borrowers.*.first_name' => ['nullable', 'string'],
            'co_borrowers.*.middle_name' => ['nullable', 'string'],
            'co_borrowers.*.last_name' => ['nullable', 'string'],
            'co_borrowers.*.name_suffix' => ['nullable', 'string'],
            'co_borrowers.*.date_of_birth' => ['nullable'],
            'co_borrowers.*.civil_status' => ['nullable', 'string'],
            'co_borrowers.*.sex' => ['nullable', 'string'],
            'co_borrowers.*.nationality' => ['nullable', 'string'],
            'co_borrowers.*.email' => ['nullable', 'string'],
            'co_borrowers.*.mobile' => ['nullable', 'string'],
            'co_borrowers.*.other_mobile' => ['nullable', 'string'],
            'co_borrowers.*.relationship_to_buyer' => ['nullable', 'string'],
            'co_borrowers.*.help_number' => ['nullable', 'string'],
            'co_borrowers.*.mothers_maiden_name' => ['nullable', 'string'],
            'co_borrowers.*.passport' => ['nullable', 'string'],
            'co_borrowers.*.date_issued' => ['nullable', 'string'],
            'co_borrowers.*.place_issued' => ['nullable', 'string'],

            'order' => ['nullable', 'array'],
            'order.sku' => ['nullable', 'string'],
            'order.seller_commission_code' => ['nullable', 'string'],
            'order.property_code' => ['nullable', 'string'],
            'order.property_type' => ['nullable', 'string'],
            'order.company_name' => ['nullable', 'string'],
            'order.project_name' => ['nullable', 'string'],
            'order.project_code' => ['nullable', 'string'],
            'order.property_name' => ['nullable', 'string'],
            'order.phase' => ['nullable', 'string'],
            'order.block' => ['nullable'],
            'order.lot' => ['nullable'],
            'order.lot_area' => ['nullable'],
            'order.floor_area' => ['nullable'],
            'order.tcp' => ['nullable'],
            'order.loan_term' => ['nullable'],
            'order.loan_interest_rate' => ['nullable'],
            'order.tct_no' => ['nullable'],
            'order.interest' => ['nullable'],
            'order.project_location' => ['nullable', 'string'],
            'order.project_address' => ['nullable', 'string'],
            'order.mrif_fee' => ['nullable', 'numeric'],
            'order.reservation_rate' => ['nullable', 'numeric'],
            'order.class_field' => ['nullable', 'string'],
            'order.segment_field' => ['nullable', 'string'],
            'order.rebooked_id_form' => ['nullable', 'numeric'],
            'order.buyer_action_form_number' => ['nullable'],
            'order.buyer_action_form_date' => ['nullable'],
            'order.cancellation_type' => ['nullable', 'string'],
            'order.cancellation_reason' => ['nullable', 'string'],
            'order.cancellation_reason2' => ['nullable', 'string'],
            'order.cancellation_remarks' => ['nullable', 'string'],

            'order.unit_type' => ['nullable', 'string'],
            'order.unit_type_interior' => ['nullable', 'string'],
            'order.house_color' => ['nullable', 'string'],
            'order.construction_status' => ['nullable'],
            'order.transaction_reference' => ['nullable', 'string'],
            'order.reservation_date' => ['nullable'],
            'order.circular_number' => ['nullable', 'string'],

            'order.term_1' => ['nullable', 'string'],
            'order.term_2' => ['nullable', 'string'],
            'order.term_3' => ['nullable', 'string'],
            'order.amort_mrisri1' => ['nullable'],
            'order.amort_mrisri2' => ['nullable'],
            'order.amort_mrisri3' => ['nullable'],
            'order.amort_nonlife1' => ['nullable'],
            'order.amort_nonlife2' => ['nullable'],
            'order.amort_nonlife3' => ['nullable'],
            'order.amort_princ_int1' => ['nullable'],
            'order.amort_princ_int2' => ['nullable'],
            'order.amort_princ_int3' => ['nullable'],
            'order.monthly_amort1' => ['nullable'],
            'order.monthly_amort2' => ['nullable'],
            'order.monthly_amort3' => ['nullable'],
            'order.equity_1_amount' => ['nullable'],
            'order.equity_1_percentage_rate' => ['nullable'],
            'order.equity_1_interest_rate' => ['nullable'],
            'order.equity_1_terms' => ['nullable'],
            'order.equity_1_monthly_payment' => ['nullable'],
            'order.amort_mrisri1' => ['nullable', 'numeric'],
            'order.amort_mrisri2' => ['nullable', 'string'],
            'order.amort_mrisri3' => ['nullable', 'string'],
            'order.amort_nonlife1' => ['nullable', 'numeric '],
            'order.amort_nonlife2' => ['nullable', 'string'],
            'order.amort_nonlife3' => ['nullable', 'string'],
            'order.amort_princ_int1' => ['nullable', 'numeric'],
            'order.amort_princ_int2' => ['nullable', 'string'],
            'order.amort_princ_int3' => ['nullable', 'string'],
            'order.monthly_amort1' => ['nullable', 'numeric'],
            'order.monthly_amort2' => ['nullable', 'string'],
            'order.monthly_amort3' => ['nullable', 'string'],
            'order.equity_1_amount' => ['nullable', 'numeric'],
            'order.equity_1_percentage_rate' => ['nullable', 'numeric'],
            'order.equity_1_interest_rate' => ['nullable', 'numeric'],
            'order.equity_1_terms' => ['nullable', 'numeric'],
            'order.equity_1_monthly_payment' => ['nullable', 'numeric'],
            'order.equity_1_effective_date' => ['nullable'],
            'order.equity_2_amount' => ['nullable'],
            'order.equity_2_percentage_rate' => ['nullable'],
            'order.equity_2_interest_rate' => ['nullable'],
            'order.equity_2_terms' => ['nullable'],

            'order.cash_outlay_1_terms' => ['nullable', 'numeric'],
            'order.cash_outlay_1_monthly_payment' => ['nullable', 'numeric'],
            'order.cash_outlay_1_effective_date' => ['nullable'],
            'order.cash_outlay_2_amount' => ['nullable', 'numeric'],
            'order.cash_outlay_2_percentage_rate' => ['nullable', 'numeric'],
            'order.cash_outlay_2_interest_rate' => ['nullable', 'numeric'],
            'order.cash_outlay_2_terms' => ['nullable', 'numeric'],
            'order.cash_outlay_2_monthly_payment' => ['nullable', 'numeric'],
            'order.cash_outlay_2_effective_date' => ['nullable'],
            'order.cash_outlay_3_amount' => ['nullable','numeric'],
            'order.cash_outlay_3_percentage_rate' => ['nullable','numeric'],
            'order.cash_outlay_3_interest_rate' => ['nullable','numeric'],
            'order.cash_outlay_3_terms' => ['nullable','numeric'],
            'order.cash_outlay_3_monthly_payment' => ['nullable', 'numeric'],
            'order.cash_outlay_3_effective_date' => ['nullable'],
            'order.page' => ['nullable'],

            'order.building' => ['nullable'],
            'order.floor' => ['nullable'],
            'order.unit' => ['nullable'],
            'order.cct' => ['nullable'],
            'order.witness1' => ['nullable', 'string'],
            'order.witness2' => ['nullable', 'string'],
            'order.buyer_extension_name' => ['nullable', 'string'],
            'order.company_acronym' => ['nullable', 'string'],
            'order.repricing_period_in_words' => ['nullable', 'string'],
            'order.repricing_period' => ['nullable'],
            'order.company_address' => ['nullable', 'string'],
            'order.exec_position' => ['nullable', 'string'],
            'order.board_resolution_date' => ['nullable'],
            'order.registry_of_deeds_address' => ['nullable', 'string'],
            'order.exec_tin' => ['nullable', 'string'],
            'order.loan_period_in_words' => ['nullable', 'string'],
            'order.spouse_address' => ['nullable', 'string'],
            'order.total_miscellaneous_fee_in_words' => ['nullable', 'string'],
            'order.tmf' => ['nullable', 'string'],

            'order.cash_outlay_1_amount' => ['nullable', 'numeric'],
            'order.cash_outlay_1_percentage_rate' => ['nullable', 'numeric'],
            'order.cash_outlay_1_interest_rate' => ['nullable', 'numeric'],
            'order.equity_2_monthly_payment' => ['nullable', 'numeric'],
            'order.equity_2_effective_date' => ['nullable'],
            'order.bp_1_amount' => ['nullable', 'numeric'],
            'order.bp_1_percentage_rate' => ['nullable'],
            'order.bp_1_interest_rate' => ['nullable'],
            'order.bp_1_terms' => ['nullable'],
            'order.bp_1_monthly_payment' => ['nullable', 'numeric'],
            'order.bp_1_effective_date' => ['nullable'],
            'order.bp_2_amount' => ['nullable'],
            'order.bp_2_percentage_rate' => ['nullable'],
            'order.bp_2_interest_rate' => ['nullable'],
            'order.bp_2_terms' => ['nullable'],
            'order.bp_2_monthly_payment' => ['nullable', 'numeric'],
            'order.bp_2_effective_date' => ['nullable'],
            'order.circular_no_312_379' => ['nullable'],
            'order.tcp_in_words' => ['nullable'],
            'order.interest_in_words' => ['nullable'],
            'order.logo' => ['nullable'],
            'order.loan_period_months' => ['nullable'],
            'order.exec_signatories' => ['nullable'],
            'order.exec_tin_no' => ['nullable'],
            'order.loan_value_after_downpayment' => ['nullable'],

            'order.date_created' => ['nullable'],
            'order.ra_date' => ['nullable'],
            'order.date_approved' => ['nullable'],
            'order.date_expiration' => ['nullable'],
            'order.os_month' => ['nullable'],
            'order.due_date' => ['nullable'],
            'order.total_payments_made' => ['nullable'],
            'order.transaction_status' => ['nullable'],
            'order.staging_status' => ['nullable'],
            'order.period_id' => ['nullable'],
            'order.date_closed' => ['nullable'],
            'order.closed_reason' => ['nullable'],
            'order.date_cancellation' => ['nullable'],

            'order.baf_number' => ['nullable'],
            'order.baf_date' => ['nullable'],
            'order.client_id_buyer' => ['nullable'],
            'order.buyer_age' => ['nullable'],
            'order.hucf_move_in_fee' => ['nullable', 'numeric'],
            'order.ltvr_slug' => ['nullable'],
            'order.repricing_period_slug' => ['nullable'],
            'order.company_tin' => ['nullable'],
            'order.yes_for_faq_solaris_project' => ['nullable'],
            'order.n_for_faq_solaris_project' => ['nullable'],
            'order.disclosure_statement_on_loan_transaction_total' => ['nullable'],
            'order.documentary_stamp' => ['nullable'],
            'order.total_deduction_from_loan_proceeds' => ['nullable'],
            'order.net_loan_proceeds' => ['nullable'],
            'order.verified_survey_return_no' => ['nullable'],
            'order.technical_description' => ['nullable'],

            'order.project_type' => ['nullable', 'string'], // Missing in Mapping
            'order.co_borrower_name' => ['nullable'], // change to co_borrower

            'order.payments' => ['nullable', 'array'],
            'order.payments.*.type' => ['nullable'],
            'order.payments.*.amount' => ['nullable'],
            'order.payments.*.payment_date' => ['nullable'],

            'order.fees.*.name' => ['nullable', 'string'],
            'order.fees.*.amount' => ['nullable'],

            'order.seller' => ['nullable', 'array'],
            'order.seller.unit' => ['nullable', 'string'],
            'order.seller.id' => ['nullable', 'string'],
            'order.seller.name' => ['nullable', 'string'],
            'order.seller.superior' => ['nullable', 'string'],
            'order.seller.team_head' => ['nullable', 'string'],
            'order.seller.chief_seller_officer' => ['nullable', 'string'],
            'order.seller.deputy_chief_seller_officer' => ['nullable', 'string'],
            'order.seller.type' => ['nullable', 'string'],
            'order.seller.reference_no' => ['nullable', 'string'],

            'order.payment_scheme' => ['nullable', 'array'],
            'order.payment_scheme.scheme' => ['nullable'],
            'order.payment_scheme.method' => ['nullable'],
            'order.payment_scheme.collectible_price' => ['nullable', 'numeric'],
            'order.payment_scheme.commissionable_amount' => ['nullable', 'numeric'],
            'order.payment_scheme.evat_percentage' => ['nullable', 'numeric'],
            'order.payment_scheme.evat_amount' => ['nullable', 'numeric'],
            'order.payment_scheme.net_total_contract_price' => ['nullable', 'numeric'],
            'order.payment_scheme.total_contract_price' => ['nullable', 'numeric'],

            'order.payment_scheme.payment' => ['nullable', 'array'],
            'order.payment_scheme.payment.*.type' => ['nullable', 'array'],
            'order.payment_scheme.payment.*.amount_paid' => ['nullable', 'numeric'],
            'order.payment_scheme.payment.*.date' => ['nullable'],
            'order.payment_scheme.payment.*.reference_number' => ['nullable'],

            'order.payment_scheme.fees' => ['nullable', 'array'],
            'order.payment_scheme.fees.*.name' => ['nullable', 'string'],
            'order.payment_scheme.fees.*.amount' => ['nullable', 'numeric'],

            'order.payment_scheme.payment_remarks' => ['nullable', 'string'],
            'order.payment_scheme.transaction_remarks' => ['nullable', 'string'],
            'order.payment_scheme.discount_rate' => ['nullable'],
            'order.payment_scheme.conditional_discount' => ['nullable', 'numeric'],
            'order.payment_scheme.transaction_sub_status' => ['nullable', 'string'],
            'order.total_selling_price' => ['nullable', 'numeric'],

            // 'co_borrower' => ['nullable', 'array'],
            'co_borrower.*.unit_lot' => ['nullable', 'string'],
            'co_borrower.*.street' => ['nullable', 'string'],
            'co_borrower.*.subdivision' => ['nullable', 'string'],
            'co_borrower.*.city' => ['nullable', 'string'],
            'co_borrower.*.province' => ['nullable', 'string'],
            'co_borrower.*.zip_code' => ['nullable', 'string'],
            'co_borrower.*.length_of_stay' => ['nullable', 'string'],
            'co_borrower.*.ownership_type' => ['nullable', 'string'],
            'co_borrower.*.birthday' => ['nullable', 'string'],
            'co_borrower.*.age' => ['nullable', 'string'],
            'co_borrower.*.gender' => ['nullable', 'string'],
            'co_borrower.*.position' => ['nullable', 'string'],
            'co_borrower.*.industry' => ['nullable', 'string'],
            'co_borrower.*.salary_gross_income' => ['nullable', 'string'],
            'co_borrower.*.company_phone_number' => ['nullable', 'string'],
            'co_borrower.*.fax' => ['nullable', 'string'],
            'co_borrower.*.company_email' => ['nullable', 'string'],

        ];
    }

    public function asController(ActionRequest $request): \Illuminate\Http\JsonResponse
    {
        $contact = $this->persist($request->validated());

        return response()->json([
            'code' => $contact->reference_code,
            'status' => $contact->exists,
        ]);
    }
}
