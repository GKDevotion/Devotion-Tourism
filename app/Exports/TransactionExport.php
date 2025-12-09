<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class TransactionExport implements FromView
{
    // protected $data;
    // protected $companyObj;
    // protected $companyBankAccountObj;
    // protected $cashAvailable;

    // public function __construct($data, $companyObj, $companyBankAccountObj, $cashAvailable)
    // {
    //     $this->data = $data;
    //     $this->companyObj = $companyObj;
    //     $this->companyBankAccountObj = $companyBankAccountObj;
    //     $this->cashAvailable = $cashAvailable;
    // }

    // public function view(): View
    // {
    //     return view('backend.pages.accounting.transactions', [
    //         'data' => $this->data,
    //         'companyObj' => $this->companyObj,
    //         'companyBankAccountObj' => $this->companyBankAccountObj,
    //         'cashAvailable' => $this->cashAvailable
    //     ]);
    // }

    public function __construct(
        protected array $data,
        protected $companyObj,
        protected $companyBankAccountObj,
        protected bool $cashAvailable
    ) {}

    public function view(): View
    {
        return view('backend.pages.accounting.transactions', [
            'data' => $this->data,
            'companyObj' => $this->companyObj,
            'companyBankAccountObj' => $this->companyBankAccountObj,
            'cashAvailable' => $this->cashAvailable,
        ]);
    }
}
