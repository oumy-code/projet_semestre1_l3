using brasilBugerC_.Models  ;

namespace brasilBugerC_.Models.ViewModels;

public class CatalogueViewModel
{
    public IEnumerable<Burger> Burgers { get; set; } = new List<Burger>();
    public IEnumerable<Menu> Menus { get; set; } = new List<Menu>();
    public string? Filtre { get; set; } // "burger", "menu", ou null pour tout
}
