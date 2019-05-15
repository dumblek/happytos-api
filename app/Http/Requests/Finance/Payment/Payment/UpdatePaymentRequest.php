<?php

namespace App\Http\Requests\Finance\Payment\Payment;

use App\Model\Master\Allocation;
use App\Http\Requests\ValidationRule;
use App\Model\Accounting\ChartOfAccount;
use Illuminate\Foundation\Http\FormRequest;
use App\Model\Finance\Payment\PaymentDetail;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rulesForm = ValidationRule::form();

        $rulesPayment = [
            'payment_account_id' => ValidationRule::foreignKey(ChartOfAccount::getTableName()),
            'disbursed' => 'required|boolean',
            // TODO validate paymentable_id is exist
            'paymentable_id' => 'required|integer|min:0',
            'paymentable_type' => 'required|string',

            'details' => 'required|array',
        ];

        $rulesPaymentDetail = [
            'details.*.chart_of_account_id' => ValidationRule::foreignKey(ChartOfAccount::getTableName()),
            'details.*.amount' => ValidationRule::price(),
            'details.*.allocation_id' => ValidationRule::foreignKeyNullable(Allocation::getTableName()),
            'details.*.referenceable_type' => [
                'required',
                function($attribute, $value, $fail) {
                    if (! PaymentDetail::referenceableIsValid($value)) {
                        $fail($attribute. ' is invalid');
                    }
                }
            ],
        ];

        return array_merge($rulesForm, $rulesPayment, $rulesPaymentDetail);
    }
}
