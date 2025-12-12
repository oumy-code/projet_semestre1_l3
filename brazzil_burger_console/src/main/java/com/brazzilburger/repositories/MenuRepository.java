package com.brazzilburger.repositories;

import com.brazzilburger.repositories.Impl.IMenuRepository;
import com.brazzilburger.config.DatabaseConfig;
import com.brazzilburger.models.CompositionMenu;
import com.brazzilburger.models.Menu;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class MenuRepository implements IMenuRepository {

    @Override
    public Menu create(Menu menu) throws SQLException {
        String sql = "INSERT INTO menu (nom, image) VALUES (?, ?) RETURNING id";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, menu.getNom());
            stmt.setString(2, menu.getImage());
            
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                menu.setId(rs.getInt("id"));
            }
            
            System.out.println("✅ Menu créé: " + menu.getNom());
            return menu;
        }
    }

    @Override
    public void addComposition(Integer idMenu, Integer idBurger, Integer idComplement, int quantite) throws SQLException {
        String sql = "INSERT INTO composition_menu (id_menu, id_burger, id_complement, quantite) VALUES (?, ?, ?, ?)";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, idMenu);
            stmt.setObject(2, idBurger);
            stmt.setObject(3, idComplement);
            stmt.setInt(4, quantite);
            
            stmt.executeUpdate();
            System.out.println("✅ Composition ajoutée au menu");
        }
    }

    @Override
    public List<Menu> findAll() throws SQLException {
        String sql = "SELECT * FROM menu ORDER BY date_creation DESC";
        List<Menu> menus = new ArrayList<>();
        
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                Menu menu = mapResultSet(rs);
                menu.setCompositions(findCompositions(menu.getId()));
                menus.add(menu);
            }
        }
        
        return menus;
    }

    @Override
    public List<Menu> findAllNonArchived() throws SQLException {
        String sql = "SELECT * FROM menu WHERE archive = false ORDER BY date_creation DESC";
        List<Menu> menus = new ArrayList<>();
        
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                Menu menu = mapResultSet(rs);
                menu.setCompositions(findCompositions(menu.getId()));
                menus.add(menu);
            }
        }
        
        return menus;
    }

    @Override
    public Menu findById(Integer id) throws SQLException {
        String sql = "SELECT * FROM menu WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            
            if (rs.next()) {
                Menu menu = mapResultSet(rs);
                menu.setCompositions(findCompositions(id));
                return menu;
            }
        }
        
        return null;
    }

    @Override
    public List<CompositionMenu> findCompositions(Integer idMenu) throws SQLException {
        String sql = "SELECT cm.*, b.nom as nom_burger, c.nom as nom_complement " +
                     "FROM composition_menu cm " +
                     "LEFT JOIN burger b ON cm.id_burger = b.id " +
                     "LEFT JOIN complement c ON cm.id_complement = c.id " +
                     "WHERE cm.id_menu = ?";
        
        List<CompositionMenu> compositions = new ArrayList<>();
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, idMenu);
            ResultSet rs = stmt.executeQuery();
            
            while (rs.next()) {
                CompositionMenu comp = new CompositionMenu();
                comp.setId(rs.getInt("id"));
                comp.setIdMenu(rs.getInt("id_menu"));
                comp.setIdBurger((Integer) rs.getObject("id_burger"));
                comp.setIdComplement((Integer) rs.getObject("id_complement"));
                comp.setQuantite(rs.getInt("quantite"));
                comp.setNomBurger(rs.getString("nom_burger"));
                comp.setNomComplement(rs.getString("nom_complement"));
                compositions.add(comp);
            }
        }
        
        return compositions;
    }

    @Override
    public void update(Menu menu) throws SQLException {
        String sql = "UPDATE menu SET nom = ?, image = ? WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, menu.getNom());
            stmt.setString(2, menu.getImage());
            stmt.setInt(3, menu.getId());
            
            stmt.executeUpdate();
            System.out.println("✅ Menu modifié: " + menu.getNom());
        }
    }

    @Override
    public void archive(Integer id) throws SQLException {
        String sql = "UPDATE menu SET archive = true WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            stmt.executeUpdate();
            System.out.println("📦 Menu archivé (id: " + id + ")");
        }
    }

    @Override
    public void deleteComposition(Integer idComposition) throws SQLException {
        String sql = "DELETE FROM composition_menu WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, idComposition);
            stmt.executeUpdate();
            System.out.println("🗑️ Composition supprimée");
        }
    }

    private Menu mapResultSet(ResultSet rs) throws SQLException {
        Menu menu = new Menu();
        menu.setId(rs.getInt("id"));
        menu.setNom(rs.getString("nom"));
        menu.setImage(rs.getString("image"));
        menu.setArchive(rs.getBoolean("archive"));
        menu.setDateCreation(rs.getTimestamp("date_creation").toLocalDateTime());
        return menu;
    }
}
