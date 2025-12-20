namespace brasilBugerC_.Models.ViewModels;

public class PanierViewModel
{
    public List<PanierItemViewModel> Items { get; set; } = new();
    
    public decimal Total => Items.Sum(i => i.SousTotal);
    
    public int NombreArticles => Items.Sum(i => i.Quantite);
}