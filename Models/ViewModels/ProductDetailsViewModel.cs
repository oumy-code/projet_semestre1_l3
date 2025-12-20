using brasilBugerC_.Models;

namespace brasilBugerC_.Models.ViewModels;

public class ProductDetailsViewModel
{
    public string Type { get; set; } = string.Empty; // "burger" ou "menu"
    public Burger? Burger { get; set; }
    public Menu? Menu { get; set; }
    public IEnumerable<Complement> ComplementsFrites { get; set; } = new List<Complement>();
    public IEnumerable<Complement> ComplementsBoissons { get; set; } = new List<Complement>();
    public int Quantite { get; set; } = 1;
    public List<int> SelectedComplementIds { get; set; } = new();
}
