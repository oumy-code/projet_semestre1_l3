package com.brazzilburger.views;

import java.sql.SQLException;
import java.util.InputMismatchException;
import java.util.List;
import java.util.Scanner;

import com.brazzilburger.models.Burger;
import com.brazzilburger.models.Complement;
import com.brazzilburger.models.Menu;
import com.brazzilburger.models.enums.ComplementType;
import com.brazzilburger.services.Impl.IMenuService; 

public class MenuView {

    private final IMenuService service; 
    private final Scanner scanner = new Scanner(System.in);

    public MenuView(IMenuService service) {
        this.service = service;
    }

    public void menuGestionMenus() {
        int choix;
        while (true) {
            System.out.println("\n╔════ GESTION DES MENUS ════╗");
            System.out.println("1. Créer un menu");
            System.out.println("2. Lister tous les menus");
            System.out.println("3. Lister menus disponibles");
            System.out.println("4. Voir détails d'un menu");
            System.out.println("5. Modifier un menu");
            System.out.println("6. Gérer compositions d'un menu");
            System.out.println("7. Archiver un menu");
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
                    case 1 -> creerMenu();
                    //case 2 -> listerMenus(false);
                    //case 3 -> listerMenus(true);
                    //case 4 -> voirDetailsMenu();
                    //case 5 -> modifierMenu();
                    //case 6 -> gererCompositions();
                    //case 7 -> archiverMenu();
                    case 0 -> { return; }
                    default -> System.out.println("❌ Choix invalide");
                }
            } catch (SQLException e) {
                System.err.println("❌ Erreur de base de données: " + e.getMessage());
            } catch (Exception e) {
                System.err.println("❌ Erreur: " + e.getMessage());
            }
        }
    }

    private void creerMenu() throws SQLException {
        System.out.print("Nom du menu: ");
        String nom = scanner.nextLine();
        System.out.print("Chemin image (ENTER pour ignorer): ");
        String image = scanner.nextLine();

        Menu menu = service.creerMenu(nom, image);
        System.out.println("✅ Menu créé ID: " + menu.getId());

        System.out.println("\n[ÉTAPE OBLIGATOIRE] Ajout d'une composition minimale (Burger + Frites + Boisson):");
        creerCompositionComplete(menu.getId());
        System.out.println("✅ Composition minimale ajoutée !");
    }

  
    

   

    
    private void creerCompositionComplete(int idMenu) throws SQLException {
        boolean burgerChoisi = false;
        boolean boissonChoisie = false;
        boolean friteChoisie = false;

        System.out.println("➡️ Composez votre menu (au moins 1 burger, 1 boisson, 1 frite)");

        List<Burger> burgers = service.getBurgersDisponibles(); 
        if (burgers.isEmpty()) {
            System.out.println("❌ Aucun burger disponible.");
            return;
        }

        while (!burgerChoisi) { 
            System.out.println("\nBurgers disponibles :");
            for (Burger b : burgers) {
                System.out.printf("%d. %s | %.2f FCFA%n", b.getId(), b.getNom(), b.getPrix());
            }
            System.out.print("ID du burger à ajouter : ");
            
            int idB;
            try {
                idB = scanner.nextInt();
                scanner.nextLine();
            } catch (InputMismatchException e) {
                System.out.println("❌ ID invalide.");
                scanner.nextLine();
                continue;
            }

            Burger burgerSelectionne = burgers.stream()
                .filter(b -> b.getId() == idB)
                .findFirst()
                .orElse(null);

            if (burgerSelectionne == null) {
                System.out.println("❌ Burger invalide ou non disponible !");
                continue;
            }

            service.ajouterComposition(idMenu, idB, null, 1); 
            burgerChoisi = true;
            System.out.println("✅ Burger ajouté : " + burgerSelectionne.getNom());

            while (true) {
                System.out.print("Voulez-vous ajouter un autre burger ? (O/N) : ");
                String rep = scanner.nextLine().trim();
                if (rep.equalsIgnoreCase("O")) {
                    burgerChoisi = false; 
                    break;
                } else {
                    break;
                }
            }
        }

        List<Complement> complements = service.getComplementsDisponibles(); 
        if (complements.isEmpty()) {
            System.out.println("❌ Aucun complément disponible.");
            return;
        }

        while (!boissonChoisie || !friteChoisie) { 
            System.out.println("\nCompléments disponibles :");
            for (Complement c : complements) {
                System.out.printf("%d. %s | %s | %.2f FCFA%n", c.getId(), c.getNom(), c.getType(), c.getPrix());
            }

            System.out.printf("\n[OBLIGATOIRE] Manque : %s%s%s\n",
                !boissonChoisie ? "Boisson" : "",
                (!boissonChoisie && !friteChoisie) ? " et " : "",
                !friteChoisie ? "Frites" : "");


            System.out.print("ID du complément à ajouter : ");
            int idC;
            try {
                idC = scanner.nextInt();
                scanner.nextLine();
            } catch (InputMismatchException e) {
                System.out.println("❌ ID invalide.");
                scanner.nextLine();
                continue;
            }
            
            Complement c = complements.stream().filter(comp -> comp.getId() == idC).findFirst().orElse(null);

            if (c == null) {
                System.out.println("❌ Complément invalide ou non disponible !");
                continue;
            }

            if (c.getType() == ComplementType.BOISSON) {
                if (!boissonChoisie) {
                    boissonChoisie = true;
                }
            } else if (c.getType() == ComplementType.FRITES) {
                if (!friteChoisie) {
                    friteChoisie = true;
                }
            } 
            
            service.ajouterComposition(idMenu, null, c.getId(), 1); 
            System.out.println("✅ Complément ajouté : " + c.getNom() + " (Type: " + c.getType() + ")");


            if (boissonChoisie && friteChoisie) {
                System.out.print("Voulez-vous ajouter un autre complément (facultatif) ? (O/N) : ");
                String rep = scanner.nextLine().trim();
                if (!rep.equalsIgnoreCase("O")) {
                    break; 
            }
        }
    }
}
}