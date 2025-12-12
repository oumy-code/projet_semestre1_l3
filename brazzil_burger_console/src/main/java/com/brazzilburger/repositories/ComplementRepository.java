package com.brazzilburger.repositories;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

import com.brazzilburger.config.DatabaseConfig;
import com.brazzilburger.models.Complement;
import com.brazzilburger.models.enums.ComplementType;
import com.brazzilburger.repositories.Impl.IComplementRepository;

public class ComplementRepository implements IComplementRepository {

    @Override
    public Complement create(Complement complement) throws SQLException {
        String sql = "INSERT INTO complement (nom, type, prix, image) VALUES (?, ?, ?, ?) RETURNING id";

        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, complement.getNom()); // nom obligatoire
            stmt.setObject(2, complement.getType().name(), java.sql.Types.OTHER); // ENUM PostgreSQL
            stmt.setBigDecimal(3, complement.getPrix());
            stmt.setString(4, complement.getImage());

            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                complement.setId(rs.getInt("id"));
            }

            System.out.println("✅ Complément créé: " + complement.getNom());
            return complement;
        }
    }

    @Override
    public List<Complement> findAll() throws SQLException {
        String sql = "SELECT * FROM complement ORDER BY date_creation DESC";
        List<Complement> complements = new ArrayList<>();

        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {

            while (rs.next()) {
                complements.add(mapResultSet(rs));
            }
        }

        return complements;
    }

    @Override
    public List<Complement> findAllNonArchived() throws SQLException {
        String sql = "SELECT * FROM complement WHERE archive = false ORDER BY date_creation DESC";
        List<Complement> complements = new ArrayList<>();

        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {

            while (rs.next()) {
                complements.add(mapResultSet(rs));
            }
        }

        return complements;
    }

    @Override
    public Complement findById(Integer id) throws SQLException {
        String sql = "SELECT * FROM complement WHERE id = ?";

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
    public void update(Complement complement) throws SQLException {
        String sql = "UPDATE complement SET nom = ?, type = ?, prix = ?, image = ? WHERE id = ?";

        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, complement.getNom());
            stmt.setObject(2, complement.getType().name(), java.sql.Types.OTHER);
            stmt.setBigDecimal(3, complement.getPrix());
            stmt.setString(4, complement.getImage());
            stmt.setInt(5, complement.getId());

            stmt.executeUpdate();
            System.out.println("✅ Complément modifié: " + complement.getNom());
        }
    }

    @Override
    public void archive(Integer id) throws SQLException {
        String sql = "UPDATE complement SET archive = true WHERE id = ?";

        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setInt(1, id);
            stmt.executeUpdate();
            System.out.println("📦 Complément archivé (id: " + id + ")");
        }
    }

    @Override
    public void unarchive(Integer id) throws SQLException {
        String sql = "UPDATE complement SET archive = false WHERE id = ?";

        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setInt(1, id);
            stmt.executeUpdate();
            System.out.println("📂 Complément désarchivé (id: " + id + ")");
        }
    }

    private Complement mapResultSet(ResultSet rs) throws SQLException {
    Complement complement = new Complement();

    complement.setId(rs.getInt("id"));
    complement.setNom(rs.getString("nom"));

    String typeStr = rs.getString("type");
    if (typeStr == null) {
        typeStr = "BOISSON"; // valeur par défaut si null
    }
    complement.setType(ComplementType.valueOf(typeStr));

    complement.setPrix(rs.getBigDecimal("prix"));
    complement.setImage(rs.getString("image"));
    complement.setArchive(rs.getBoolean("archive"));
    complement.setDateCreation(rs.getTimestamp("date_creation").toLocalDateTime());

    return complement;
}

}
