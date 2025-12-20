using brasilBugerC_.Models.ViewModels;

namespace brasilBugerC_.Services;

public interface IPanierService
{
    PanierViewModel GetPanier();
    void AddBurger(int burgerId, string nom, decimal prix, int quantite, List<int>? complementIds = null);
    void AddMenu(int menuId, string nom, decimal prix, int quantite);
    void AddComplement(int complementId, string nom, decimal prix, int quantite);
    void UpdateQuantite(string itemKey, int quantite);
    void RemoveItem(string itemKey);
    void ClearPanier();
    decimal GetTotal();
}
