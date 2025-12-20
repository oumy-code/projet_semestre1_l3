using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Models;

public class Commande
{
    public int Id { get; set; }
    public int IdClient { get; set; }
    public int? IdGestionnaire { get; set; }
    public int? IdZone { get; set; }
    public int? IdLivreur { get; set; }
    public DateTime DateCommande { get; set; }
    public decimal MontantTotal { get; set; }
    public EtatCommande Etat { get; set; }
    public TypeRecuperation TypeRecuperation { get; set; }
    public string? AdresseLivraison { get; set; }
    public DateTime DateModification { get; set; }
    
    // Navigation properties
    public Client? Client { get; set; }
    public Zone? Zone { get; set; }
    public List<LigneCommande> LignesCommande { get; set; } = new();
    public Paiement? Paiement { get; set; }
}