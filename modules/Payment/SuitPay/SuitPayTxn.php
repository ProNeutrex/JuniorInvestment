<?php

namespace Payment\SuitPay;

use Payment\Transaction\BaseTxn;

class SuitPayTxn extends BaseTxn
{
    public function __construct($txnInfo)
    {
        parent::__construct($txnInfo);
    }

    public function deposit()
    {
        /**
         * Criar Pagamento
         *
         * @param \Session $session  A sessão atual do usuário.
         * @param float $amount  O valor do pagamento.
         * @param string $currency  A moeda usada para o pagamento.
         */

        $payment = SuitPayTxn::api()->payments()->create([
            'amount' => [
                'currency' => $this->currency,
                'value' => (string) $this->amount . '.00',
            ],
            'description' => $this->siteName,
            'webhookUrl' => route('ipn.mollie', 'reftrn=' . $this->txn),
            'redirectUrl' => route('status.pending'),
        ]);

        $payment = SuitPayTxn::api()->payments()->get($payment->id);
        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }
}
