using brasilBugerC_.Models;

namespace brasilBugerC_.Models.ViewModels;
public class CommandeViewModel
{
    public IEnumerable<Commande> Commandes { get; set; } = new List<Commande>();
}
