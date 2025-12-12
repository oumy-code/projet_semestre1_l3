package com.brazzilburger.models;

import java.math.BigDecimal;
import java.time.LocalDateTime;

public class Burger {
    private Integer id;
    private String nom;
    private BigDecimal prix;
    private String image;
    private boolean archive;
    private LocalDateTime dateCreation;

    public Burger() {}

    public Burger(String nom, BigDecimal prix, String image) {
        this.nom = nom;
        this.prix = prix;
        this.image = image;
        this.archive = false;
    }

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

    @Override
    public String toString() {
        return String.format("Burger[id=%d, nom=%s, prix=%.0f FCFA, archive=%s]", 
            id, nom, prix, archive ? "Oui" : "Non");
    }
}