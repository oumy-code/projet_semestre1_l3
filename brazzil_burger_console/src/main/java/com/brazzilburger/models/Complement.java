package com.brazzilburger.models;



import java.math.BigDecimal;
import java.time.LocalDateTime;
import com.brazzilburger.models.enums.ComplementType;

public class Complement {

    private Integer id;
    private String nom;
    private BigDecimal prix;
    private String image;
    private boolean archive;
    private LocalDateTime dateCreation;

    private ComplementType type; // 🔵 AJOUT

    public Complement() {}

    // 🔵 Nouveau constructeur AVEC type
    public Complement(String nom, BigDecimal prix, String image, ComplementType type) {
        this.nom = nom;
        this.prix = prix;
        this.image = image;
        this.type = type;
        this.archive = false;
    }

    // ---- Getters & Setters ----

    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public BigDecimal getPrix() { return prix; }
    public void setPrix(BigDecimal prix) { this.prix = prix; }

    public String getImage() { return image; }
    public void setImage(String image) { this.image = image; }

    public boolean isArchive() { return archive; }
    public void setArchive(boolean archive) { this.archive = archive; }

    public LocalDateTime getDateCreation() { return dateCreation; }
    public void setDateCreation(LocalDateTime dateCreation) { this.dateCreation = dateCreation; }

    public ComplementType getType() { return type; }
    public void setType(ComplementType type) { this.type = type; }

    @Override
    public String toString() {
        return String.format(
            "Complement[id=%d, nom=%s, prix=%.0f FCFA, type=%s, archive=%s]",
            id, nom, prix, type, archive ? "Oui" : "Non"
        );
    }
}
