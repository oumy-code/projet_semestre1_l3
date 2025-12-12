package com.brazzilburger.services.Impl;



import com.brazzilburger.models.Burger;
import com.brazzilburger.models.Complement;
import com.brazzilburger.models.Menu;
import java.sql.SQLException;
import java.util.List;

public interface IMenuService {

    Menu creerMenu(String nom, String imagePath) throws SQLException;
    void ajouterComposition(int idMenu, Integer idBurger, Integer idComplement, int quantite) throws SQLException;
     List<Burger> getBurgersDisponibles() throws SQLException;

    List<Complement> getComplementsDisponibles() throws SQLException;
     List<Menu> listerMenus(boolean disponiblesUniquement) throws SQLException;
    Menu getMenuById(int id) throws SQLException;
    Menu modifierMenu(Menu menu, String nom, String imagePath) throws SQLException;
    void archiverMenu(int id) throws SQLException;



}