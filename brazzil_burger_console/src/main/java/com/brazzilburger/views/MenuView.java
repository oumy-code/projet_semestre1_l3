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
                    case 2 -> listerMenus(false);
                    case 3 -> listerMenus(true);
                    case 4 -> voirDetailsMenu();
                    case 5 -> modifierMenu();
                    case 6 -> gererCompositions();
                    case 7 -> archiverMenu();
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
 private void listerMenus(boolean disponibles) throws SQLException {
        List<Menu> menus = service.listerMenus(disponibles); 
        if (menus.isEmpty()) {
            System.out.println("Aucun menu trouvé.");
            return;
        }
        for (Menu m : menus) {
           
            int compositionSize = m.getCompositions() != null ? m.getCompositions().size() : 0;
            System.out.printf("ID:%d | %s | %d compositions | %s%n",
                    m.getId(), m.getNom(), compositionSize,
                    m.isArchive() ? "📦 Archivé" : "✅ Disponible");
        }
    }
    private void voirDetailsMenu() throws SQLException {
        listerMenus(false);
        System.out.print("ID du menu: ");
        int id;
        try {
            id = scanner.nextInt();
            scanner.nextLine();
        } catch (InputMismatchException e) {
            System.out.println("❌ ID invalide.");
            scanner.nextLine();
            return;
        }

        Menu menu = service.getMenuById(id); // Appel du service
        if (menu == null) {
            System.out.println("❌ Menu non trouvé");
            return;
        }
       
        System.out.println("\n--- Détails du Menu " + menu.getId() + " ---");
        System.out.println("Nom: " + menu.getNom());
        System.out.println("Statut: " + (menu.isArchive() ? "Archivé" : "Disponible"));
        System.out.println("Compositions:");
        if (menu.getCompositions().isEmpty()) System.out.println("(Aucune)");
        else menu.getCompositions().forEach(System.out::println);
    }

     private void modifierMenu() throws SQLException {
        listerMenus(false);
        System.out.print("ID du menu à modifier: ");
        int id;
        try {
            id = scanner.nextInt();
            scanner.nextLine();
        } catch (InputMismatchException e) {
            System.out.println("❌ ID invalide.");
            scanner.nextLine();
            return;
        }

        Menu menu = service.getMenuById(id); 
        if (menu == null) {
            System.out.println("❌ Menu non trouvé");
            return;
        }
        System.out.print("Nouveau nom [" + menu.getNom() + "]: ");
        String nom = scanner.nextLine();
        System.out.print("Nouvelle image (chemin ou ENTER): ");
        String image = scanner.nextLine();
        
        service.modifierMenu(menu, nom, image); 
        System.out.println("✅ Menu modifié!");
    }
        private void archiverMenu() throws SQLException {
        listerMenus(true);
        System.out.print("ID du menu à archiver: ");
        int id;
        try {
            id = scanner.nextInt();
            scanner.nextLine();
        } catch (InputMismatchException e) {
            System.out.println("❌ ID invalide.");
            scanner.nextLine();
            return;
        }
        service.archiverMenu(id); 
        System.out.println("✅ Menu archivé!");
    }
      private void gererCompositions() throws SQLException {
        listerMenus(false);
        System.out.print("ID du menu à gérer: ");
        int idMenu;
        try {
            idMenu = scanner.nextInt();
            scanner.nextLine();
        } catch (InputMismatchException e) {
            System.out.println("❌ ID invalide.");
            scanner.nextLine();
            return;
        }
        
        Menu menu = service.getMenuById(idMenu);
        if (menu == null) {
            System.out.println("❌ Menu non trouvé.");
            return;
        }

        while (true) {
            System.out.println("\n--- GESTION COMPOSITIONS MENU " + idMenu + " ---");
            System.out.println("1. Ajouter une composition simple (Burger OU Complément)"); // NOUVELLE OPTION
            System.out.println("3. Voir compositions actuelles");
            System.out.println("4. Supprimer une composition");
            System.out.println("0. Retour");
            System.out.print("Choix: ");
            
            int choix;
            try {
                choix = scanner.nextInt();
                scanner.nextLine();
            } catch (InputMismatchException e) {
                System.out.println("❌ Choix invalide.");
                scanner.nextLine();
                continue;
            }


            switch (choix) {
                case 1 -> ajouterCompositionSimple(idMenu); 
              
                case 3 -> {
                  
                    Menu m = service.getMenuById(idMenu); 
                    System.out.println("\nCompositions actuelles:");
                    if (m != null && m.getCompositions() != null) m.getCompositions().forEach(System.out::println);
                    else System.out.println("(Aucune)");
                }
                case 4 -> supprimerComposition(idMenu);
                case 0 -> { return; }
                default -> System.out.println("❌ Choix invalide.");
            }
        }
    }
     private void supprimerComposition(int idMenu) throws SQLException {
        Menu menu = service.getMenuById(idMenu);
        if (menu == null || menu.getCompositions() == null || menu.getCompositions().isEmpty()) {
             System.out.println("❌ Menu non trouvé ou aucune composition à supprimer.");
             return;
        }
        
      
        System.out.println("\nCompositions actuelles:");
        menu.getCompositions().forEach(comp -> System.out.println("ID:" + comp.getId() + " - " + comp));
        
        System.out.print("ID de la composition à supprimer: ");
        int idComp;
        try {
            idComp = scanner.nextInt();
            scanner.nextLine();
        } catch (InputMismatchException e) {
            System.out.println("❌ ID invalide.");
            scanner.nextLine();
            return;
        }
        
        service.supprimerComposition(idComp); 
        System.out.println("✅ Composition supprimée!");
    }
  private void ajouterCompositionSimple(int idMenu) throws SQLException {
        System.out.println("\n--- AJOUT COMPOSITION SIMPLE ---");
        System.out.println("1. Ajouter un Burger");
        System.out.println("2. Ajouter un Complément (Frites, Boisson, etc.)");
        System.out.print("Choix (1 ou 2): ");

        int choix;
        try {
            choix = scanner.nextInt();
            scanner.nextLine();
        } catch (InputMismatchException e) {
            System.out.println("❌ Choix invalide.");
            scanner.nextLine();
            return;
        }

        switch (choix) {
            case 1 -> ajouterUnSeulBurger(idMenu);
            case 2 -> ajouterUnSeulComplement(idMenu);
            default -> System.out.println("❌ Choix invalide.");
        }
    }
      private void ajouterUnSeulBurger(int idMenu) throws SQLException {
        List<Burger> burgers = service.getBurgersDisponibles();
        if (burgers.isEmpty()) {
            System.out.println("❌ Aucun burger disponible.");
            return;
        }

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
            return;
        }

        Burger burgerSelectionne = burgers.stream()
            .filter(b -> b.getId() == idB)
            .findFirst()
            .orElse(null);

        if (burgerSelectionne == null) {
            System.out.println("❌ Burger invalide ou non disponible !");
            return;
        }

        
        service.ajouterComposition(idMenu, idB, null, 1);
        System.out.println("✅ Burger ajouté : " + burgerSelectionne.getNom() + " au menu " + idMenu);
    }
    private void ajouterUnSeulComplement(int idMenu) throws SQLException {
        List<Complement> complements = service.getComplementsDisponibles();
        if (complements.isEmpty()) {
            System.out.println("❌ Aucun complément disponible.");
            return;
        }

        System.out.println("\nCompléments disponibles :");
        for (Complement c : complements) {
            System.out.printf("%d. %s | %s | %.2f FCFA%n", c.getId(), c.getNom(), c.getType(), c.getPrix());
        }
        System.out.print("ID du complément à ajouter : ");

        int idC;
        try {
            idC = scanner.nextInt();
            scanner.nextLine();
        } catch (InputMismatchException e) {
            System.out.println("❌ ID invalide.");
            scanner.nextLine();
            return;
        }

        Complement complementSelectionne = complements.stream()
            .filter(c -> c.getId() == idC)
            .findFirst()
            .orElse(null);

        if (complementSelectionne == null) {
            System.out.println("❌ Complément invalide ou non disponible !");
            return;
        }

      
        service.ajouterComposition(idMenu, null, idC, 1);
        System.out.println("✅ Complément ajouté : " + complementSelectionne.getNom() + " au menu " + idMenu);
    }




}