package com.brazzilburger.repositories;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

import com.brazzilburger.config.DatabaseConfig;
import com.brazzilburger.models.Burger;
import com.brazzilburger.repositories.Impl.IBurgerRepository;

public class BurgerRepository implements IBurgerRepository {

    @Override
    public Burger create(Burger burger) throws SQLException {
        String sql =  "INSERT INTO burger (nom, prix, image) VALUES (?, ?, ?) RETURNING id";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, burger.getNom());
            stmt.setBigDecimal(2, burger.getPrix());
            stmt.setString(3, burger.getImage());
            
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                burger.setId(rs.getInt("id"));
            }
            
            System.out.println("✅ Burger créé: " + burger.getNom());
            return burger;
        }
    }

    @Override
    public List<Burger> findAll() throws SQLException {
        String sql = "SELECT * FROM burger ORDER BY date_creation DESC";
        List<Burger> burgers = new ArrayList<>();
        
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                burgers.add(mapResultSet(rs));
            }
        }
        return burgers;
    }

    @Override
    public List<Burger> findAllNonArchived() throws SQLException {
        String sql = "SELECT * FROM burger WHERE archive = false ORDER BY date_creation DESC";
        List<Burger> burgers = new ArrayList<>();
        
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                burgers.add(mapResultSet(rs));
            }
        }
        
        return burgers;
    }

    @Override
    public Burger findById(Integer id) throws SQLException {
        String sql = "SELECT * FROM burger WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            
            if (rs.next()) {
                return mapResultSet(rs);
            }
        }
        return null;
    }

    @Override
    public void update(Burger burger) throws SQLException {
        String sql = "UPDATE burger SET nom = ?, prix = ?, image = ? WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, burger.getNom());
            stmt.setBigDecimal(2, burger.getPrix());
            stmt.setString(3, burger.getImage());
            stmt.setInt(4, burger.getId());
            
            stmt.executeUpdate();
            System.out.println("✅ Burger modifié: " + burger.getNom());
        }
    }

    @Override
    public void archive(Integer id) throws SQLException {
        String sql = "UPDATE burger SET archive = true WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            stmt.executeUpdate();
            System.out.println("📦 Burger archivé (id: " + id + ")");
        }
    }

    @Override
    public void unarchive(Integer id) throws SQLException {
        String sql = "UPDATE burger SET archive = false WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            stmt.executeUpdate();
            System.out.println("📂 Burger désarchivé (id: " + id + ")");
        }
    }

    private Burger mapResultSet(ResultSet rs) throws SQLException {
        Burger burger = new Burger();
        burger.setId(rs.getInt("id"));
        burger.setNom(rs.getString("nom"));
        burger.setPrix(rs.getBigDecimal("prix"));
        burger.setImage(rs.getString("image"));
        burger.setArchive(rs.getBoolean("archive"));
        burger.setDateCreation(rs.getTimestamp("date_creation").toLocalDateTime());
        return burger;
    }
}
