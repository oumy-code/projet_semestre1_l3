namespace brasilBugerC_.Models;

public class Zone
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public decimal PrixLivraison { get; set; }
    public string? Quartiers { get; set; }
    public DateTime DateCreation { get; set; }
}
