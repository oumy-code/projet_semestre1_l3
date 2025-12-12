package com.brazzilburger.models;

public class CompositionMenu {
    private Integer id;
    private Integer idMenu;
    private Integer idBurger;
    private Integer idComplement;
    private int quantite;
    private String nomBurger;
    private String nomComplement;

    public CompositionMenu() {}

    public CompositionMenu(Integer idMenu, Integer idBurger, Integer idComplement, int quantite) {
        this.idMenu = idMenu;
        this.idBurger = idBurger;
        this.idComplement = idComplement;
        this.quantite = quantite;
    }

    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }
    
    public Integer getIdMenu() { return idMenu; }
    public void setIdMenu(Integer idMenu) { this.idMenu = idMenu; }
    
    public Integer getIdBurger() { return idBurger; }
    public void setIdBurger(Integer idBurger) { this.idBurger = idBurger; }
    
    public Integer getIdComplement() { return idComplement; }
    public void setIdComplement(Integer idComplement) { this.idComplement = idComplement; }
    
    public int getQuantite() { return quantite; }
    public void setQuantite(int quantite) { this.quantite = quantite; }
    
    public String getNomBurger() { return nomBurger; }
    public void setNomBurger(String nomBurger) { this.nomBurger = nomBurger; }
    
    public String getNomComplement() { return nomComplement; }
    public void setNomComplement(String nomComplement) { this.nomComplement = nomComplement; }

    @Override
    public String toString() {
        String type = idBurger != null ? "Burger: " + nomBurger : "Complément: " + nomComplement;
        return String.format("  - %s (x%d)", type, quantite);
    }
}