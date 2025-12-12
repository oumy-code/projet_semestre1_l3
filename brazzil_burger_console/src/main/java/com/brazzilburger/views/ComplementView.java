package com.brazzilburger.views;

import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.InputMismatchException;
import java.util.List;
import java.util.Scanner;

import com.brazzilburger.models.Complement;
import com.brazzilburger.models.enums.ComplementType;
import com.brazzilburger.services.Impl.IComplementService; // Utilisation de l'interface

public class ComplementView {

    // Utilisation de l'interface pour le service
    private final IComplementService service; 
    private final Scanner scanner = new Scanner(System.in);

    // Le constructeur prend l'interface en paramètre
    public ComplementView(IComplementService service) {
        this.service = service;
    }

    public void menuGestionComplements() {
        int choix;
        while (true) {
            System.out.println("\n╔════ GESTION DES COMPLÉMENTS ════╗");
            System.out.println("1. Ajouter un complément");
            System.out.println("2. Lister tous les compléments");
            System.out.println("3. Lister compléments disponibles");
            System.out.println("4. Modifier un complément");
            System.out.println("5. Archiver un complément");
            System.out.println("6. Désarchiver un complément");
            System.out.println("0. Retour");
            System.out.print("Choix: ");

            try {
                choix = scanner.nextInt();
                scanner.nextLine();
            } catch (InputMismatchException e) {
                System.out.println("❌ Choix invalide. Veuillez entrer un nombre.");
                scanner.nextLine();
                continue;
            }

            try {
                switch (choix) {
                    case 1 -> ajouterComplement();
                    //case 2 -> listerComplements(false);
                    //case 3 -> listerComplements(true);
                    //case 4 -> modifierComplement();
                    //case 5 -> archiverComplement();
                    //case 6 -> desarchiverComplement();
                    case 0 -> { return; }
                    default -> System.out.println("❌ Choix invalide");
                }
            } catch (SQLException e) {
                System.err.println("❌ Erreur de base de données: " + e.getMessage());
            } catch (Exception e) {
                System.err.println("❌ Une erreur inattendue est survenue: " + e.getMessage());
            }
        }
    }

    private void ajouterComplement() throws SQLException {
        System.out.println("\n--- AJOUTER UN COMPLÉMENT ---");
        System.out.print("Nom: ");
        String nom = scanner.nextLine();

        BigDecimal prix = null;
        while (prix == null) {
            try {
                System.out.print("Prix: ");
                prix = scanner.nextBigDecimal();
                scanner.nextLine();
            } catch (InputMismatchException e) {
                System.out.println("❌ Veuillez entrer un prix valide (nombre).");
                scanner.nextLine();
            }
        }

        System.out.print("Chemin image (ENTER pour ignorer): ");
        String image = scanner.nextLine();

        // 🔵 Choix du TYPE (BOISSON/ FRITES) - Logique de saisie dans la View
        ComplementType type = choisirTypeComplement();

        // Appel du Service avec les données saisies
        Complement complement = service.creerComplement(nom, prix, image, type);

        System.out.println("✅ Complément créé: " + complement.getNom() + " (ID: " + complement.getId() + ")");
    }

    private ComplementType choisirTypeComplement() {
        while (true) {
            System.out.println("\nType de complément :");
            System.out.println("1. BOISSON");
            System.out.println("2. FRITES");
            System.out.print("Choix type: ");
            
            try {
                int choix = scanner.nextInt();
                scanner.nextLine(); // Consomme le retour à la ligne
                
                return switch (choix) {
                    case 1 -> ComplementType.BOISSON;
                    case 2 -> ComplementType.FRITES;
                    default -> {
                        System.out.println("❌ Choix de type invalide. Veuillez choisir 1 ou 2.");
                        yield null;
                    }
                };
            } catch (InputMismatchException e) {
                System.out.println("❌ Entrée invalide. Veuillez entrer un nombre (1 ou 2).");
                scanner.nextLine(); 
            }
        }
    }
}