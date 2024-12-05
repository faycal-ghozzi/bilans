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
            'structure_financière' => $this->calculerRatiosStructureFinanciere($donneesN, $donneesNMoins1),
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
            'n' => $this->getValeurParId($donneesN, 'Résultat d\'exploitation'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Résultat d\'exploitation'),
        ];

        $capitauxPropres = [
            'n' => $this->getValeurParId($donneesN, 'Total des capitaux propres après résultat de l\'exercice'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des capitaux propres après résultat de l\'exercice'),
        ];

        $dettesFinancieres = [
            'n' => $this->getValeurParId($donneesN, 'Total des passifs non courants'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des passifs non courants'),
        ];

        $ebe = [
            'n' => $this->getValeurParId($donneesN, 'EBE'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'EBE'),
        ];

        $ca = [
            'n' => $this->getValeurParId($donneesN, 'Revenus'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Revenus'),
        ];

        $caf = [
            'n' => $this->calculateCAF($donneesN),
            'n-1' => $this->calculateCAF($donneesNMoins1),
        ];

        return [
            'retour_exploitation' => $this->calculerEvolutions(
                $capitauxPropres['n'] + $dettesFinancieres['n'] ? $resultatExploitation['n'] / ($capitauxPropres['n'] + $dettesFinancieres['n']) : 0,
                $capitauxPropres['n-1'] + $dettesFinancieres['n-1'] ? $resultatExploitation['n-1'] / ($capitauxPropres['n-1'] + $dettesFinancieres['n-1']) : 0
            ),
            'retour_net_capitaux_propres' => $this->calculerEvolutions(
                $capitauxPropres['n'] ? $resultatExploitation['n'] / $capitauxPropres['n'] : 0,
                $capitauxPropres['n-1'] ? $resultatExploitation['n-1'] / $capitauxPropres['n-1'] : 0
            ),
            'ebe_ca' => $this->calculerEvolutions(
                $ca['n'] ? $ebe['n'] / $ca['n'] : 0,
                $ca['n-1'] ? $ebe['n-1'] / $ca['n-1'] : 0
            ),
            'caf_ca' => $this->calculerEvolutions(
                $ca['n'] ? $caf['n'] / $ca['n'] : 0,
                $ca['n-1'] ? $caf['n-1'] / $ca['n-1'] : 0
            ),
        ];
    }

    private function calculerRatiosStructureFinanciere($donneesN, $donneesNMoins1)
    {
        $ressourcesStables = [
            'n' => $this->getValeurParId($donneesN, 'Total des capitaux propres et passifs'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des capitaux propres et passifs'),
        ];

        $actifsImmobiles = [
            'n' => $this->getValeurParId($donneesN, 'Total actifs immobilisés'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total actifs immobilisés'),
        ];

        $actifCirculant = [
            'n' => $this->getValeurParId($donneesN, 'Total actifs courants'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total actifs courants'),
        ];

        $passifCirculant = [
            'n' => $this->getValeurParId($donneesN, 'Total passifs courants'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total passifs courants'),
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
            'ressources_stables_actifs_immobilises' => $this->calculerEvolutions($fr['n'], $fr['n-1']),
            'actif_circulant_passif_circulant' => $this->calculerEvolutions($bfr['n'], $bfr['n-1']),
        ];
    }

    private function calculateCAF($data)
    {
        $resultatNet = $this->getValeurParId($data, 'Résultat net de l\'exercice');
        $dotations = $this->getValeurParId($data, 'Dotations aux amortissements et aux provisions');
        $reprises = $this->getValeurParId($data, 'Reprises sur provisions');
        $produitsCession = $this->getValeurParId($data, 'Produits de cession d\'immobilisations');
        $valeursComptables = $this->getValeurParId($data, 'Valeurs comptables des immobilisations cédées');

        return $resultatNet + $dotations - $reprises - $produitsCession + $valeursComptables;
    }

    private function calculerRatiosLiquidite($donneesN, $donneesNMoins1)
    {
        $actifCirculant = [
            'n' => $this->getValeurParId($donneesN, 'Total des actifs courants'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des actifs courants'),
        ];

        $tresorerieActif = [
            'n' => $this->getValeurParId($donneesN, 'Liquidités et équivalents de liquidités'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Liquidités et équivalents de liquidités'),
        ];

        $passifCirculant = [
            'n' => $this->getValeurParId($donneesN, 'Total des passifs courants'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des passifs courants'),
        ];

        $tresoreriePassif = [
            'n' => $this->getValeurParId($donneesN, 'Concours bancaires et autres passifs financiers'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Concours bancaires et autres passifs financiers'),
        ];

        return [
            'liquidite_generale' => $this->calculerEvolutions(
                $passifCirculant['n'] + $tresoreriePassif['n'] ? ($actifCirculant['n'] + $tresorerieActif['n']) / ($passifCirculant['n'] + $tresoreriePassif['n']) : 0,
                $passifCirculant['n-1'] + $tresoreriePassif['n-1'] ? ($actifCirculant['n-1'] + $tresorerieActif['n-1']) / ($passifCirculant['n-1'] + $tresoreriePassif['n-1']) : 0
            ),
            'liquidite_reduite' => $this->calculerEvolutions(
                $passifCirculant['n'] ? $actifCirculant['n'] / $passifCirculant['n'] : 0,
                $passifCirculant['n-1'] ? $actifCirculant['n-1'] / $passifCirculant['n-1'] : 0
            ),
            'liquidite_tresorerie' => $this->calculerEvolutions(
                $passifCirculant['n'] + $tresoreriePassif['n'] ? $tresorerieActif['n'] / ($passifCirculant['n'] + $tresoreriePassif['n']) : 0,
                $passifCirculant['n-1'] + $tresoreriePassif['n-1'] ? $tresorerieActif['n-1'] / ($passifCirculant['n-1'] + $tresoreriePassif['n-1']) : 0
            ),
        ];
    }

    private function calculerRatiosEndettement($donneesN, $donneesNMoins1)
    {
        $chargesFinancieres = [
            'n' => $this->getValeurParId($donneesN, 'Charges financières nettes'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Charges financières nettes'),
        ];

        $ca = [
            'n' => $this->getValeurParId($donneesN, 'Revenus'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Revenus'),
        ];

        $ebe = [
            'n' => $this->getValeurParId($donneesN, 'EBE'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'EBE'),
        ];

        $capitauxPropres = [
            'n' => $this->getValeurParId($donneesN, 'Total des capitaux propres après résultat de l\'exercice'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des capitaux propres après résultat de l\'exercice'),
        ];

        $dettesFinancieres = [
            'n' => $this->getValeurParId($donneesN, 'Total des passifs non courants'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des passifs non courants'),
        ];

        $ebitda = [
            'n' => $this->calculateEBITDA($donneesN),
            'n-1' => $this->calculateEBITDA($donneesNMoins1),
        ];

        return [
            'charges_financieres_ca' => $this->calculerEvolutions(
                $ca['n'] ? $chargesFinancieres['n'] / $ca['n'] : 0,
                $ca['n-1'] ? $chargesFinancieres['n-1'] / $ca['n-1'] : 0
            ),
            'charges_financieres_ebe' => $this->calculerEvolutions(
                $ebe['n'] ? $chargesFinancieres['n'] / $ebe['n'] : 0,
                $ebe['n-1'] ? $chargesFinancieres['n-1'] / $ebe['n-1'] : 0
            ),
            'dettes_financieres_capitaux_propres' => $this->calculerEvolutions(
                $capitauxPropres['n'] ? $dettesFinancieres['n'] / $capitauxPropres['n'] : 0,
                $capitauxPropres['n-1'] ? $dettesFinancieres['n-1'] / $capitauxPropres['n-1'] : 0
            ),
            'ebitda_charges_financieres' => $this->calculerEvolutions(
                $chargesFinancieres['n'] ? $ebitda['n'] / $chargesFinancieres['n'] : 0,
                $chargesFinancieres['n-1'] ? $ebitda['n-1'] / $chargesFinancieres['n-1'] : 0
            ),
            'dettes_financieres_ebitda' => $this->calculerEvolutions(
                $ebitda['n'] ? $dettesFinancieres['n'] / $ebitda['n'] : 0,
                $ebitda['n-1'] ? $dettesFinancieres['n-1'] / $ebitda['n-1'] : 0
            ),
        ];
    }

    private function calculerRatiosSolvabilite($donneesN, $donneesNMoins1)
    {
        $capitauxPropres = [
            'n' => $this->getValeurParId($donneesN, 'Total des capitaux propres après résultat de l\'exercice'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des capitaux propres après résultat de l\'exercice'),
        ];

        $ressourcesStables = [
            'n' => $this->getValeurParId($donneesN, 'Total des capitaux propres et passifs'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des capitaux propres et passifs'),
        ];

        $totalBilan = [
            'n' => $this->getValeurParId($donneesN, 'Total des actifs'),
            'n-1' => $this->getValeurParId($donneesNMoins1, 'Total des actifs'),
        ];

        return [
            'capitaux_propres_ressources_stables' => $this->calculerEvolutions(
                $ressourcesStables['n'] ? $capitauxPropres['n'] / $ressourcesStables['n'] : 0,
                $ressourcesStables['n-1'] ? $capitauxPropres['n-1'] / $ressourcesStables['n-1'] : 0
            ),
            'capitaux_propres_total_bilan' => $this->calculerEvolutions(
                $totalBilan['n'] ? $capitauxPropres['n'] / $totalBilan['n'] : 0,
                $totalBilan['n-1'] ? $capitauxPropres['n-1'] / $totalBilan['n-1'] : 0
            ),
        ];
    }

    private function calculateEBITDA($data)
    {
        // Retrieve necessary values
        $resultatExploitation = $this->getValeurParId($data, 'Résultat d\'exploitation'); // Operating result
        $dotations = $this->getValeurParId($data, 'Dotations aux amortissements et aux provisions'); // Depreciation and provisions

        // Calculate EBITDA
        return $resultatExploitation + $dotations;
    }
}
