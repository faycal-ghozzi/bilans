<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class FsEntryPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entryPoints = [
            //  Actifs
            //  Actifs Non Courants
            ['label' => 'Immobilisations incorporelles', 'category' => 'Actifs non courants', 'role' => 'Actifs Immobilises', 'decoration' => null, 'type' => 'incorporelles'],
            ['label' => 'Amortissements - Immobilisations incorporelles', 'category' => 'Actifs non courants' , 'role' => 'Actifs Immobilises - amortissement', 'decoration' => null, 'type' => 'incorporelles'],
            ['label' => 'Immobilisations corporelles', 'category' => 'Actifs non courants' , 'role' => 'Actifs Immobilises', 'decoration' => null, 'type' => 'corporelles'],
            ['label' => 'Amortissements - Immobilisations corporelles', 'category' => 'Actifs non courants' , 'role' => 'Actifs Immobilises - amortissement', 'decoration' => null, 'type' => 'corporelles'],
            ['label' => 'Immobilisations financières', 'category' => 'Actifs non courants' , 'role' => 'Actifs Immobilises', 'decoration' => null, 'type' => 'financières'],
            ['label' => 'Provisions - Immobilisations financières', 'category' => 'Actifs non courants' , 'role' => 'Actifs Immobilises - provision', 'decoration' => null, 'type' => 'financières'],
            ['label' => 'Total actifs immobilisés', 'category' => 'Actifs non courants' , 'role' => 'Actifs Immobilises - total', 'decoration' => 'bold', 'type' => null],
            ['label' => 'Autres actifs non courants', 'category' => 'Actifs non courants' , 'role' => 'Actif non courant', 'decoration' => 'bold', 'type' => null],
            ['label' => 'Total des actifs non courants', 'category' => 'Actifs non courants' , 'role' => 'Actif non courant - total', 'decoration' => 'stripe', 'type' => null],
            // Actifs Courants
            ['label' => 'Stocks', 'category' => 'Actifs courants' , 'role' => 'Actif Courant', 'decoration' => null, 'type' => null],
            ['label' => 'Provisions - Stocks', 'category' => 'Actifs courants' , 'role' => 'Actif Courant - provision', 'decoration' => null, 'type' => null],
            ['label' => 'Clients et comptes rattachés', 'category' => 'Actifs courants' , 'role' => 'Actif Courant', 'decoration' => null, 'type' => null],
            ['label' => 'Provisions - Clients et comptes rattachés', 'category' => 'Actifs courants' , 'role' => 'Actif Courant - provision', 'decoration' => null, 'type' => null],
            ['label' => 'Autres actifs courants', 'category' => 'Actifs courants' , 'role' => 'Actif Courant', 'decoration' => null, 'type' => null],
            ['label' => 'Placements et autres actifs financiers', 'category' => 'Actifs courants' , 'role' => 'Actif Courant', 'decoration' => null, 'type' => null],
            ['label' => 'Liquidités et équivalents de liquidités', 'category' => 'Actifs courants' , 'role' => 'Actif Courant', 'decoration' => null, 'type' => null],
            ['label' => 'Total des actifs courants', 'category' => 'Actifs courants' , 'role' => 'Actif Courant - total', 'decoration' => 'stripe', 'type' => null],
            // Total Des Actifs
            ['label' => 'Total des actifs', 'category' => 'Actifs' , 'role' => 'Actifs - total', 'decoration' => 'stripe', 'type' => null],
            
            // Capitaux Propres et passifs
            // Captaux Propres
            ['label' => 'Capital social', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres', 'decoration' => null, 'type' => null],
            ['label' => 'Réserves légales', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres', 'decoration' => null, 'type' => null], 
            ['label' => 'Autres capitaux propres', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres', 'decoration' => null, 'type' => null], 
            ['label' => 'Réserves spéciales de réévaluation', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres', 'decoration' => null, 'type' => null], 
            ['label' => 'Résultats reportés', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres', 'decoration' => null, 'type' => null], 
            ['label' => 'Modifications comptables', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres', 'decoration' => null, 'type' => null],
            ['label' => 'Total des capitaux propres avant résultat de l\'exercice', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres - total av re', 'decoration' => 'bold', 'type' => null], 
            ['label' => 'Résultat de l\'exercice', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres', 'decoration' => null, 'type' => null], 
            ['label' => 'Total des capitaux propres après résultat de l\'exercice', 'category' => 'Capitaux propres' , 'role' => 'Capitaux propres - total', 'decoration' => 'stripe', 'type' => null],
            // Passifs Non Courants
            ['label' => 'Emprunts', 'category' => 'Passifs non courants' , 'role' => 'Passif non courant', 'decoration' => null, 'type' => null],
            ['label' => 'Provisions', 'category' => 'Passifs non courants' , 'role' => 'Passif non courant', 'decoration' => null, 'type' => null],
            ['label' => 'Autres passifs financiers', 'category' => 'Passifs non courants' , 'role' => 'Passif non courant', 'decoration' => null, 'type' => null],
            ['label' => 'Total des passifs non courants', 'category' => 'Passifs non courants' , 'role' => 'Passif non courant - total', 'decoration' => 'stripe', 'type' => null],
            // Passifs Courants
            ['label' => 'Fournisseurs et comptes rattachés', 'category' => 'Passifs courants' , 'role' => 'Passif courant', 'decoration' => null, 'type' => null],
            ['label' => 'Autres passifs courants', 'category' => 'Passifs courants' , 'role' => 'Passif courant', 'decoration' => null, 'type' => null],
            ['label' => 'Concours bancaires et autres passifs financiers', 'category' => 'Passifs courants' , 'role' => 'Passif courant', 'decoration' => null, 'type' => null],
            ['label' => 'Total des passifs courants', 'category' => 'Passifs courants' , 'role' => 'Passif courant - total', 'decoration' => 'stripe', 'type' => null],
            // Total Des Passifs
            ['label' => 'Total des passifs', 'category' => 'Passifs' , 'role' => 'Passifs - total', 'decoration' => 'stripe', 'type' => null],
            // Total Des Capitaux Propres et Passifs
            ['label' => 'Total des capitaux propres et passifs', 'category' => 'Capitaux Propres et Passifs' , 'role' => 'Capitaux propres et passifs', 'decoration' => 'stripe', 'type' => null],

            // Etat de resultat
            // Produits d'exploitation
            ['label' => 'Revenus', 'category' => 'Résultat de l\'exercice', 'role' => 'Produits exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Autres produits d\'exploitation', 'category' => 'Résultat de l\'exercice', 'role' => 'Produits exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Production immobilisée', 'category' => 'Résultat de l\'exercice', 'role' => 'Produits exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Total Produits d\'exploitation', 'category' => 'Résultat de l\'exercice', 'role' => 'Produits exploitation - total', 'decoration' => 'bold', 'type' => null],
            
            ['label' => 'Variation des stocks des produits finis et des encours', 'category' => 'Résultat de l\'exercice', 'role' => 'Charges exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Achats de marchandises consommés', 'category' => 'Résultat de l\'exercice', 'role' => 'Charges exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Achats d\'approvisionnements consommés', 'category' => 'Résultat de l\'exercice', 'role' => 'Charges exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Charges de personnel', 'category' => 'Résultat de l\'exercice', 'role' => 'Charges exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Dotations aux amortissements et aux provisions', 'category' => 'Résultat de l\'exercice', 'role' => 'Charges exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Autres charges d\'exploitation', 'category' => 'Résultat de l\'exercice', 'role' => 'Charges exploitation', 'decoration' => null, 'type' => null],
            ['label' => 'Total charges d\'exploitation', 'category' => 'Résultat de l\'exercice', 'role' => 'Charges exploitation - total', 'decoration' => 'bold', 'type' => null],

            ['label' => 'Résultat d\'exploitation ', 'category' => 'Résultat de l\'exercice', 'role' => 'Resultat exploitation - total', 'decoration' => 'stripe', 'type' => null],

            ['label' => 'Charges financières nettes', 'category' => 'Résultat de l\'exercice', 'role' => 'Activites ordinaires', 'decoration' => null, 'type' => null],
            ['label' => 'Produits des placements', 'category' => 'Résultat de l\'exercice', 'role' => 'Activites ordinaires', 'decoration' => null, 'type' => null],
            ['label' => 'Autres gains ordinaires', 'category' => 'Résultat de l\'exercice', 'role' => 'Activites ordinaires - gains', 'decoration' => null, 'type' => null],
            ['label' => 'Autres pertes ordinaires', 'category' => 'Résultat de l\'exercice', 'role' => 'Activites ordinaires', 'decoration' => null, 'type' => null],
            ['label' => 'Résultat des activités ordinaires avant impôt', 'category' => 'Résultat de l\'exercice', 'role' => 'Activites ordinaires - total', 'decoration' => 'stripe', 'type' => null],

            ['label' => 'Impôt sur les bénéfices', 'category' => 'Résultat de l\'exercice', 'role' => 'Impots', 'decoration' => null, 'type' => null],
            ['label' => 'Résultat des activités ordinaires après impôt', 'category' => 'Résultat de l\'exercice', 'role' => 'Impots - total', 'decoration' => 'stripe', 'type' => null],

            ['label' => 'Eléments extraordinaires (Gains/Pertes)', 'category' => 'Résultat de l\'exercice', 'role' => 'Elements extraordinaires', 'decoration' => null, 'type' => null],

            ['label' => 'Résultat net de l\'exercice', 'category' => 'Résultat de l\'exercice', 'role' => 'Resultat net', 'decoration' => 'stripe', 'type' => null],
            ['label' => 'Effet des modifications comptables', 'category' => 'Résultat de l\'exercice', 'role' => 'Modifications comptables', 'decoration' => null, 'type' => null],
            ['label' => 'Résultat après modifications comptables', 'category' => 'Résultat de l\'exercice', 'role' => 'Resultat exercice', 'decoration' => 'stripe', 'type' => null],
        ];

        DB::table('fs_entry_points')->insert($entryPoints);
    }
}
