namespace brasilBugerC_.Models;

public class CompositionMenu
{
    public int Id { get; set; }
    public int IdMenu { get; set; }
    public int? IdBurger { get; set; }
    public int? IdComplement { get; set; }
    public int Quantite { get; set; }
    
    // Navigation properties
    public Burger? Burger { get; set; }
    public Complement? Complement { get; set; }
}