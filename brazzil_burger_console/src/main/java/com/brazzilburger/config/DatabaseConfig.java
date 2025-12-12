package com.brazzilburger.config;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

import io.github.cdimascio.dotenv.Dotenv;

public class DatabaseConfig {
    private static Dotenv dotenv = Dotenv.configure().ignoreIfMissing().load();
    
    private static final String URL = dotenv.get("DATABASE_URL", "jdbc:postgresql://localhost:5432/brasilburger");
    private static final String USER = dotenv.get("DATABASE_USER", "postgres");
    private static final String PASSWORD = dotenv.get("DATABASE_PASSWORD", "");

    public static Connection getConnection() throws SQLException {
        try {
            Class.forName("org.postgresql.Driver");
            return DriverManager.getConnection(URL, USER, PASSWORD);
        } catch (ClassNotFoundException e) {
            throw new SQLException("Driver PostgreSQL non trouvé", e);
        }
    }

    public static void testConnection() {
        try (Connection conn = getConnection()) {
            if (conn != null) {
                System.out.println("✅ Connexion à la base de données réussie!");
                System.out.println("📍 Base de données: " + conn.getCatalog());
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur de connexion: " + e.getMessage());
            e.printStackTrace();
        }
    }
}