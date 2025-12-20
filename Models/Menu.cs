namespace brasilBugerC_.Models;

public class Menu
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public string? Image { get; set; }
    public bool Archive { get; set; }
    public DateTime DateCreation { get; set; }
    public decimal Prix { get; set; } // Prix calculé
    public List<CompositionMenu> Compositions { get; set; } = new();
}