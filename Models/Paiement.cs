using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Models;

public class Paiement
{
    public int Id { get; set; }
    public int IdCommande { get; set; }
    public DateTime DatePaiement { get; set; }
    public decimal Montant { get; set; }
    public ModePaiement ModePaiement { get; set; }
    public StatutPaiement Statut { get; set; }
}