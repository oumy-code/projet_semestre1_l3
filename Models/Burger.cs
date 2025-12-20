namespace brasilBugerC_.Models;

public class Burger
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public decimal Prix { get; set; }
    public string? Image { get; set; }
    public bool Archive { get; set; }
    public DateTime DateCreation { get; set; }
}
