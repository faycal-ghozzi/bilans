<?php

namespace App\Services;

use App\Models\FinancialStatement;
use App\Models\FinancialStatementFile;
use Carbon\Carbon;

class FinancialRatioService
{
    public function calculerRatios($id)
    {
        $file = FinancialStatementFile::with('company')->findOrFail($id);

        $dateN = Carbon::parse($file->date)->format('Y-m-d');
        $dateNMoins1 = Carbon::parse($file->date)->subYear()->format('Y-m-d');

        $financialStatements = FinancialStatement::with('entryPoint')
            ->where('company_id', $file->company_id)
            ->whereIn('date', [$dateN, $dateNMoins1])
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        $donneesN = $financialStatements[$dateN] ?? collect([]);
        $donneesNMoins1 = $financialStatements[$dateNMoins1] ?? collect([]);

        return [
            'rentabilité' => $this->calculerRatiosRentabilite($donneesN, $donneesNMoins1),
            'structure financière' => $this->calculerRatiosStructureFinanciere($donneesN, $donneesNMoins1),
            'liquidité' => $this->calculerRatiosLiquidite($donneesN, $donneesNMoins1),
            'endettement' => $this->calculerRatiosEndettement($donneesN, $donneesNMoins1),
            'solvabilité' => $this->calculerRatiosSolvabilite($donneesN, $donneesNMoins1),
        ];
    }

    private function calculerEvolutions($valeurN, $valeurNMoins1)
    {
        $absolue = $valeurN - $valeurNMoins1;
        $pourcentage = $valeurNMoins1 != 0 ? ($absolue / $valeurNMoins1) * 100 : 0;

        return [
            'valeur_n' => $valeurN,
            'valeur_n_1' => $valeurNMoins1,
            'evolution_absolue' => $absolue,
            'evolution_pourcentage' => $pourcentage,
        ];
    }

    private function getValeurParId($data, $id)
    {
        return $data
            ->filter(fn($item) => $item->entry_point_id === $id)
            ->sum('value');
    }

    private function calculerRatiosRentabilite($donneesN, $donneesNMoins1)
    {
        $resultatExploitation = [
            'n' => $this->getValeurParId($donneesN, 49), // ID Résultat d'exploitation 
            'n-1' => $this->getValeurParId($donneesNMoins1, 49),
        ];

        $capitauxPropres = [
            'n' => $this->getValeurParId($donneesN, 27), // ID Total des capitaux propres après résultat de l\'exercice
            'n-1' => $this->getValeurParId($donneesNMoins1, 27),
        ];

        $dettesFinancieres = [
            'n' => $this->getValeurParId($donneesN, 31), // ID Total des passifs non courants
            'n-1' => $this->getValeurParId($donneesNMoins1, 31),
        ];

        $ebe = [
            'n' => $this->getValeurParId($donneesN, 'EBE'), // to verify
            'n-1' => $this->getValeurParId($donneesNMoins1, 'EBE'),
        ];

        $ca = [
            'n' => $this->getValeurParId($donneesN, 38), // ID Revenus
            'n-1' => $this->getValeurParId($donneesNMoins1, 38),
        ];

        // $caf = [
        //     'n' => $this->calculateCAF($donneesN),
        //     'n-1' => $this->calculateCAF($donneesNMoins1),
        // ];

        return [
            'Retour exploitation' => $this->calculerEvolutions(
                $capitauxPropres['n'] + $dettesFinancieres['n'] ? $resultatExploitation['n'] / ($capitauxPropres['n'] + $dettesFinancieres['n']) : 0,
                $capitauxPropres['n-1'] + $dettesFinancieres['n-1'] ? $resultatExploitation['n-1'] / ($capitauxPropres['n-1'] + $dettesFinancieres['n-1']) : 0
            ),
            'Retour net capitaux propres' => $this->calculerEvolutions(
                $capitauxPropres['n'] ? $resultatExploitation['n'] / $capitauxPropres['n'] : 0,
                $capitauxPropres['n-1'] ? $resultatExploitation['n-1'] / $capitauxPropres['n-1'] : 0
            ),
            // 'ebe_ca' => $this->calculerEvolutions(
            //     $ca['n'] ? $ebe['n'] / $ca['n'] : 0,
            //     $ca['n-1'] ? $ebe['n-1'] / $ca['n-1'] : 0
            // ),
            // 'caf_ca' => $this->calculerEvolutions(
            //     $ca['n'] ? $caf['n'] / $ca['n'] : 0,
            //     $ca['n-1'] ? $caf['n-1'] / $ca['n-1'] : 0
            // ),
        ];
    }

    private function calculerRatiosStructureFinanciere($donneesN, $donneesNMoins1)
    {
        $ressourcesStables = [
            'n' => $this->getValeurParId($donneesN, 37), // ID Total des capitaux propres et passifs
            'n-1' => $this->getValeurParId($donneesNMoins1, 37),
        ];

        $actifsImmobiles = [
            'n' => $this->getValeurParId($donneesN, 7), // ID Total actifs immobilisés
            'n-1' => $this->getValeurParId($donneesNMoins1, 7),
        ];

        $actifCirculant = [
            'n' => $this->getValeurParId($donneesN, 17), // ID Total actifs courants
            'n-1' => $this->getValeurParId($donneesNMoins1, 17),
        ];

        $passifCirculant = [
            'n' => $this->getValeurParId($donneesN, 35), // ID Total passifs courants
            'n-1' => $this->getValeurParId($donneesNMoins1, 35),
        ];

        $fr = [
            'n' => $ressourcesStables['n'] - $actifsImmobiles['n'],
            'n-1' => $ressourcesStables['n-1'] - $actifsImmobiles['n-1'],
        ];

        $bfr = [
            'n' => $actifCirculant['n'] - $passifCirculant['n'],
            'n-1' => $actifCirculant['n-1'] - $passifCirculant['n-1'],
        ];

        return [
            'Ressources stables actifs immobilises' => $this->calculerEvolutions($fr['n'], $fr['n-1']),
            'Actif circulant passif circulant' => $this->calculerEvolutions($bfr['n'], $bfr['n-1']),
        ];
    }

    // private function calculateCAF($data)
    // {
    //     $resultatNet = $this->getValeurParId($data, 58); // ID Résultat net de l'exercice
    //     $dotations = $this->getValeurParId($data, 46); // ID Dotations aux amortissements et aux provisions
    //     $reprises = $this->getValeurParId($data, 'Reprises sur provisions'); // to verify
    //     $produitsCession = $this->getValeurParId($data, 'Produits de cession d\'immobilisations'); // to verify
    //     $valeursComptables = $this->getValeurParId($data, 'Valeurs comptables des immobilisations cédées'); // to verify

    //     return $resultatNet + $dotations - $reprises - $produitsCession + $valeursComptables;
    // }

    private function calculerRatiosLiquidite($donneesN, $donneesNMoins1)
    {
        $actifCirculant = [
            'n' => $this->getValeurParId($donneesN, 17), // ID Total des actifs courants
            'n-1' => $this->getValeurParId($donneesNMoins1, 17),
        ];

        $tresorerieActif = [
            'n' => $this->getValeurParId($donneesN, 16), // ID Liquidités et équivalents de liquidités
            'n-1' => $this->getValeurParId($donneesNMoins1, 16),
        ];

        $passifCirculant = [
            'n' => $this->getValeurParId($donneesN, 35), // ID Total des passifs courants
            'n-1' => $this->getValeurParId($donneesNMoins1, 35),
        ];

        $tresoreriePassif = [
            'n' => $this->getValeurParId($donneesN, 34), // ID Concours bancaires et autres passifs financiers
            'n-1' => $this->getValeurParId($donneesNMoins1, 34),
        ];

        return [
            'Liquidite generale' => $this->calculerEvolutions(
                $passifCirculant['n'] + $tresoreriePassif['n'] ? ($actifCirculant['n'] + $tresorerieActif['n']) / ($passifCirculant['n'] + $tresoreriePassif['n']) : 0,
                $passifCirculant['n-1'] + $tresoreriePassif['n-1'] ? ($actifCirculant['n-1'] + $tresorerieActif['n-1']) / ($passifCirculant['n-1'] + $tresoreriePassif['n-1']) : 0
            ),
            'Liquidite reduite' => $this->calculerEvolutions(
                $passifCirculant['n'] ? $actifCirculant['n'] / $passifCirculant['n'] : 0,
                $passifCirculant['n-1'] ? $actifCirculant['n-1'] / $passifCirculant['n-1'] : 0
            ),
            'Liquidite tresorerie' => $this->calculerEvolutions(
                $passifCirculant['n'] + $tresoreriePassif['n'] ? $tresorerieActif['n'] / ($passifCirculant['n'] + $tresoreriePassif['n']) : 0,
                $passifCirculant['n-1'] + $tresoreriePassif['n-1'] ? $tresorerieActif['n-1'] / ($passifCirculant['n-1'] + $tresoreriePassif['n-1']) : 0
            ),
        ];
    }

    private function calculerRatiosEndettement($donneesN, $donneesNMoins1)
    {
        $chargesFinancieres = [
            'n' => $this->getValeurParId($donneesN, 50), // ID Charges financières nettes
            'n-1' => $this->getValeurParId($donneesNMoins1, 50),
        ];

        $ca = [
            'n' => $this->getValeurParId($donneesN, 38), // ID Revenus
            'n-1' => $this->getValeurParId($donneesNMoins1, 38),
        ];

        // $ebe = [
        //     'n' => $this->getValeurParId($donneesN, 'EBE'), // to verify
        //     'n-1' => $this->getValeurParId($donneesNMoins1, 'EBE'),
        // ];

        $capitauxPropres = [
            'n' => $this->getValeurParId($donneesN, 27), // ID Total des capitaux propres après résultat de l'exercice
            'n-1' => $this->getValeurParId($donneesNMoins1, 27),
        ];

        $dettesFinancieres = [
            'n' => $this->getValeurParId($donneesN, 31), // ID Total des passifs non courants
            'n-1' => $this->getValeurParId($donneesNMoins1, 31),
        ];

        $ebitda = [
            'n' => $this->calculateEBITDA($donneesN),
            'n-1' => $this->calculateEBITDA($donneesNMoins1),
        ];

        return [
            'Charges financieres CA' => $this->calculerEvolutions(
                $ca['n'] ? $chargesFinancieres['n'] / $ca['n'] : 0,
                $ca['n-1'] ? $chargesFinancieres['n-1'] / $ca['n-1'] : 0
            ),
            // 'charges_financieres_ebe' => $this->calculerEvolutions(
            //     $ebe['n'] ? $chargesFinancieres['n'] / $ebe['n'] : 0,
            //     $ebe['n-1'] ? $chargesFinancieres['n-1'] / $ebe['n-1'] : 0
            // ),
            'Dettes financieres capitaux propres' => $this->calculerEvolutions(
                $capitauxPropres['n'] ? $dettesFinancieres['n'] / $capitauxPropres['n'] : 0,
                $capitauxPropres['n-1'] ? $dettesFinancieres['n-1'] / $capitauxPropres['n-1'] : 0
            ),
            'EBITDA charges financieres' => $this->calculerEvolutions(
                $chargesFinancieres['n'] ? $ebitda['n'] / $chargesFinancieres['n'] : 0,
                $chargesFinancieres['n-1'] ? $ebitda['n-1'] / $chargesFinancieres['n-1'] : 0
            ),
            'Dettes financieres ebitda' => $this->calculerEvolutions(
                $ebitda['n'] ? $dettesFinancieres['n'] / $ebitda['n'] : 0,
                $ebitda['n-1'] ? $dettesFinancieres['n-1'] / $ebitda['n-1'] : 0
            ),
        ];
    }

    private function calculerRatiosSolvabilite($donneesN, $donneesNMoins1)
    {
        $capitauxPropres = [
            'n' => $this->getValeurParId($donneesN, 27),
            'n-1' => $this->getValeurParId($donneesNMoins1, 27),
        ];

        $ressourcesStables = [
            'n' => $this->getValeurParId($donneesN, 37),
            'n-1' => $this->getValeurParId($donneesNMoins1, 37),
        ];

        $totalBilan = [
            'n' => $this->getValeurParId($donneesN, 18),
            'n-1' => $this->getValeurParId($donneesNMoins1, 18),
        ];

        return [
            'Capitaux propres ressources stables' => $this->calculerEvolutions(
                $ressourcesStables['n'] ? $capitauxPropres['n'] / $ressourcesStables['n'] : 0,
                $ressourcesStables['n-1'] ? $capitauxPropres['n-1'] / $ressourcesStables['n-1'] : 0
            ),
            'Capitaux propres total bilan' => $this->calculerEvolutions(
                $totalBilan['n'] ? $capitauxPropres['n'] / $totalBilan['n'] : 0,
                $totalBilan['n-1'] ? $capitauxPropres['n-1'] / $totalBilan['n-1'] : 0
            ),
        ];
    }

    private function calculateEBITDA($data)
    {
        $resultatExploitation = $this->getValeurParId($data, 49);
        $dotations = $this->getValeurParId($data, 46);

        return $resultatExploitation + $dotations;
    }
}
