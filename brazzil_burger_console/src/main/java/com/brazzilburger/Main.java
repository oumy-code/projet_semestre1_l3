package com.brazzilburger;
import java.util.Scanner;

import com.brazzilburger.config.DatabaseConfig;
import com.brazzilburger.repositories.BurgerRepository;
import com.brazzilburger.repositories.ComplementRepository;
import com.brazzilburger.repositories.Impl.IBurgerRepository;
import com.brazzilburger.repositories.Impl.IComplementRepository;
import com.brazzilburger.repositories.Impl.IMenuRepository;
import com.brazzilburger.repositories.MenuRepository;
import com.brazzilburger.services.BurgerService;
import com.brazzilburger.services.ComplementService;
import com.brazzilburger.services.MenuService;

import com.brazzilburger.services.Impl.IBurgerService;
import com.brazzilburger.services.Impl.IComplementService;
import com.brazzilburger.services.Impl.IMenuService;
import com.brazzilburger.views.BurgerView;
import com.brazzilburger.views.ComplementView;
import com.brazzilburger.views.MenuView;

public class Main {
    private static final Scanner scanner = new Scanner(System.in);

    public static void main(String[] args) {
      
        IBurgerRepository burgerRepo = new BurgerRepository();
        IComplementRepository complementRepo = new ComplementRepository();
        IMenuRepository menuRepo = new MenuRepository();

       
        IBurgerService burgerService = new BurgerService(burgerRepo); 
     
        IComplementService complementService = new ComplementService(complementRepo); 
       
        IMenuService menuService = new MenuService(menuRepo, burgerRepo, complementRepo);

      
        BurgerView burgerView = new BurgerView(burgerService);
        ComplementView complementView = new ComplementView(complementService);
        MenuView menuView = new MenuView(menuService); 

        afficherBanniere();
        DatabaseConfig.testConnection();

        menuPrincipal(burgerView, menuView, complementView);

        System.out.println("\n👋 Au revoir!");
        scanner.close();
    }

    private static void afficherBanniere() {
        System.out.println("\n╔══════════════════════════════════════════════════════╗");
        System.out.println("║                                                      ║");
        System.out.println("║           🍔 BRASIL BURGER - CONSOLE 🍔            ║");
        System.out.println("║                                                      ║");
        System.out.println("║          Gestion des Ressources Restaurant           ║");
        System.out.println("║                                                      ║");
        System.out.println("╚══════════════════════════════════════════════════════╝\n");
    }

    private static void menuPrincipal(BurgerView burgerView,
                                      MenuView menuView,
                                      ComplementView complementView) {
        while (true) {
            System.out.println("\n╔════════════════════════════════════╗");
            System.out.println("║      🏠 MENU PRINCIPAL             ║");
            System.out.println("╚════════════════════════════════════╝");
            System.out.println("1. 🍔 Gestion des Burgers");
            System.out.println("2. 📦 Gestion des Menus");
            System.out.println("3. 🥤 Gestion des Compléments");
            System.out.println("4. ℹ️  À propos");
            System.out.println("0. 🚪 Quitter");
            System.out.print("➤ Votre choix: ");

            int choix;
            try {
                choix = scanner.nextInt();
                scanner.nextLine();
            } catch (java.util.InputMismatchException e) {
                System.out.println("❌ Saisie invalide. Veuillez entrer un chiffre.");
                scanner.nextLine(); 
                continue;
            }

            switch (choix) {
                case 1 -> burgerView.menuGestionBurgers();
                case 2 -> menuView.menuGestionMenus();
                case 3 -> complementView.menuGestionComplements();
                case 4 -> afficherAPropos();
                case 0 -> { return; }
                default -> System.out.println("❌ Choix invalide. Veuillez réessayer.");
            }
        }
    }

    private static void afficherAPropos() {
        System.out.println("\n╔══════════════════════════════════════════════════════╗");
        System.out.println("║              ℹ️  À PROPOS                             ║");
        System.out.println("╚══════════════════════════════════════════════════════╝\n");
        System.out.println("📱 Application: Brasil Burger - Console Java");
        System.out.println("🎓 Projet: Gestion Commande Restaurant");
        System.out.println("🏫 Niveau: L3 ISM - Semestre 1\n");
        System.out.println("📋 Fonctionnalités:");
        System.out.println("  • Gestion complète des burgers (CRUD + Archive)");
        System.out.println("  • Gestion des menus avec compositions");
        System.out.println("  • Gestion des compléments (boissons, frites...)");
        System.out.println("  • Upload d'images vers Cloudinary");
        System.out.println("  • Connexion à PostgreSQL (Neon)\n");
        System.out.println("🛠️  Technologies:");
        System.out.println("  • Java 17");
        System.out.println("  • Maven");
        System.out.println("  • PostgreSQL (Neon)");
        System.out.println("  • Cloudinary\n");
        System.out.println("═══════════════════════════════════════════════════════");
        System.out.println("Appuyez sur ENTER pour continuer...");
        scanner.nextLine();
    }
}