package com.brazzilburger.models;

import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

public class Menu {
    private Integer id;
    private String nom;
    private String image;
    private boolean archive;
    private LocalDateTime dateCreation;
    private List<CompositionMenu> compositions;

    public Menu() {
        this.compositions = new ArrayList<>();
    }

    public Menu(String nom, String image) {
        this.nom = nom;
        this.image = image;
        this.archive = false;
        this.compositions = new ArrayList<>();
    }

    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }
    
    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }
    
    public String getImage() { return image; }
    public void setImage(String image) { this.image = image; }
    
    public boolean isArchive() { return archive; }
    public void setArchive(boolean archive) { this.archive = archive; }
    
    public LocalDateTime getDateCreation() { return dateCreation; }
    public void setDateCreation(LocalDateTime dateCreation) { this.dateCreation = dateCreation; }
    
    public List<CompositionMenu> getCompositions() { return compositions; }
    public void setCompositions(List<CompositionMenu> compositions) { this.compositions = compositions; }

    @Override
    public String toString() {
        return String.format("Menu[id=%d, nom=%s, compositions=%d, archive=%s]", 
            id, nom, compositions.size(), archive ? "Oui" : "Non");
    }
}