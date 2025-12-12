package com.brazzilburger.services;


import com.brazzilburger.services.Impl.IMenuService; // Import de l'interface
import java.sql.SQLException;
import java.util.List;

import com.brazzilburger.config.CloudinaryConfig;
import com.brazzilburger.models.Burger;
import com.brazzilburger.models.Complement;
import com.brazzilburger.models.Menu;
import com.brazzilburger.repositories.Impl.IBurgerRepository;
import com.brazzilburger.repositories.Impl.IComplementRepository;
import com.brazzilburger.repositories.Impl.IMenuRepository;

public class MenuService implements IMenuService {

    private final IMenuRepository menuRepository;
    private final IBurgerRepository burgerRepository;
    private final IComplementRepository complementRepository;

    public MenuService(IMenuRepository menuRepository,
                       IBurgerRepository burgerRepository,
                       IComplementRepository complementRepository) {
        this.menuRepository = menuRepository;
        this.burgerRepository = burgerRepository;
        this.complementRepository = complementRepository;
    }

    public MenuService() {
        this(new com.brazzilburger.repositories.MenuRepository(),
             new com.brazzilburger.repositories.BurgerRepository(),
             new com.brazzilburger.repositories.ComplementRepository());
    }

  
    @Override
    public Menu creerMenu(String nom, String imagePath) throws SQLException {
        String imageUrl = null;
        if (imagePath != null && !imagePath.isEmpty()) {
            imageUrl = CloudinaryConfig.uploadImage(imagePath, "menus");
        }
        Menu menu = new Menu(nom, imageUrl);
        menuRepository.create(menu);
        return menu;
    }

    @Override
    public void ajouterComposition(int idMenu, Integer idBurger, Integer idComplement, int quantite) throws SQLException {
        menuRepository.addComposition(idMenu, idBurger, idComplement, quantite);
    }
     @Override
    public List<Burger> getBurgersDisponibles() throws SQLException {
        return burgerRepository.findAllNonArchived();
    }

    @Override
    public List<Complement> getComplementsDisponibles() throws SQLException {
        return complementRepository.findAllNonArchived();
    }
      @Override
    public List<Menu> listerMenus(boolean disponiblesUniquement) throws SQLException {
        return disponiblesUniquement ? menuRepository.findAllNonArchived() : menuRepository.findAll();
    }
    @Override
    public Menu getMenuById(int id) throws SQLException {
        return menuRepository.findById(id);
    }
     @Override
    public Menu modifierMenu(Menu menu, String nom, String imagePath) throws SQLException {
        if (nom != null && !nom.isEmpty()) menu.setNom(nom);
        if (imagePath != null && !imagePath.isEmpty()) {
            String imageUrl = CloudinaryConfig.uploadImage(imagePath, "menus");
            if (imageUrl != null) menu.setImage(imageUrl);
        }
        menuRepository.update(menu);
        return menu;
    }
    
}
