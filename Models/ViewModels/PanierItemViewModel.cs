namespace brasilBugerC_.Models.ViewModels   ;

public class PanierItemViewModel
{
    public string Key { get; set; } = string.Empty;
    public string Type { get; set; } = string.Empty; // "Burger", "Menu", "Complement"
    public int? BurgerId { get; set; }
    public int? MenuId { get; set; }
    public int? ComplementId { get; set; }
    public string Nom { get; set; } = string.Empty;
    public decimal PrixUnitaire { get; set; }
    public int Quantite { get; set; }
    public decimal SousTotal { get; set; }
    public List<int> ComplementIds { get; set; } = new();
}