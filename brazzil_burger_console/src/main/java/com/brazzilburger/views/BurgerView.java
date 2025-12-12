package com.brazzilburger.views;




import com.brazzilburger.models.Burger;
import com.brazzilburger.services.Impl.IBurgerService; // Utilise l'interface
import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.InputMismatchException;
import java.util.List;
import java.util.Scanner;

public class BurgerView {

    // Utiliser l'interface pour le service est une bonne pratique
    private final IBurgerService service; 
    private final Scanner scanner = new Scanner(System.in);

    public BurgerView(IBurgerService service) {
        this.service = service;
    }

    public void menuGestionBurgers() {
        int choix;
        while (true) {
            System.out.println("\n╔════════ GESTION DES BURGERS ════════╗");
            System.out.println("1. Ajouter un burger");
            System.out.println("2. Lister tous les burgers");
            System.out.println("3. Lister burgers disponibles");
            System.out.println("4. Modifier un burger");
            System.out.println("5. Archiver un burger");
            System.out.println("6. Désarchiver un burger");
            System.out.println("0. Retour");
            System.out.print("Choix: ");

            try {
                choix = scanner.nextInt();
                scanner.nextLine(); // Consomme le retour à la ligne
            } catch (InputMismatchException e) {
                System.out.println("❌ Choix invalide. Veuillez entrer un nombre.");
                scanner.nextLine(); // Consomme l'entrée invalide
                continue;
            }

            try {
                switch (choix) {
                    case 1 -> ajouterBurger();
                    //case 2 -> listerBurgers(false);
                    //case 3 -> listerBurgers(true);
                    //case 4 -> modifierBurger();
                    //case 5 -> archiverBurger();
                    //case 6 -> desarchiverBurger();
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

    private void ajouterBurger() throws SQLException {
        System.out.println("\n--- AJOUTER UN BURGER ---");
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
                scanner.nextLine(); // Consomme l'entrée invalide
            }
        }
        
        System.out.print("Chemin image (ENTER pour ignorer): ");
        String image = scanner.nextLine();

        // Appel de la logique métier dans le service
        Burger burger = service.creerBurger(nom, prix, image);
        System.out.println("✅ Burger créé: " + burger.getNom() + " (ID: " + burger.getId() + ")");
    }

   
}