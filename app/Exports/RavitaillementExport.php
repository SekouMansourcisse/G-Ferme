<?php

namespace App\Exports;

use App\Models\Operation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RavitaillementExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Operation::where('typeOperation', 'ravitaillement')->get([
            'Date_op',
            'NomPrenomFournisseur',
            'Produit',
            'TotalRavitaillement',
            'totalRemise',
            'montant_payé',
            'montantDette'
        ]);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Fournisseur',
            'Produit',
            'Montant Ravitaillement',
            'Remise',
            'Montant payé',
            'Reste à payer'
        ];
    }
}

