using brasilBugerC_.Helpers;
using brasilBugerC_.Models.ViewModels;

namespace brasilBugerC_.Services;

public class PanierService : IPanierService
{
    private readonly SessionHelper _sessionHelper;

    public PanierService(SessionHelper sessionHelper)
    {
        _sessionHelper = sessionHelper;
    }

    public PanierViewModel GetPanier()
    {
        var panier = _sessionHelper.GetPanier<PanierViewModel>();
        return panier ?? new PanierViewModel();
    }

    private void SavePanier(PanierViewModel panier)
    {
        _sessionHelper.SetPanier(panier);
    }

    public void AddBurger(int burgerId, string nom, decimal prix, int quantite, List<int>? complementIds = null)
    {
        var panier = GetPanier();
        
        var itemKey = $"burger_{burgerId}";
        if (complementIds != null && complementIds.Any())
        {
            itemKey += $"_comp_{string.Join("-", complementIds.OrderBy(x => x))}";
        }

        var existingItem = panier.Items.FirstOrDefault(i => i.Key == itemKey);
        
        if (existingItem != null)
        {
            existingItem.Quantite += quantite;
            existingItem.SousTotal = existingItem.PrixUnitaire * existingItem.Quantite;
        }
        else
        {
            panier.Items.Add(new PanierItemViewModel
            {
                Key = itemKey,
                Type = "Burger",
                BurgerId = burgerId,
                Nom = nom,
                PrixUnitaire = prix,
                Quantite = quantite,
                SousTotal = prix * quantite,
                ComplementIds = complementIds ?? new List<int>()
            });
        }

        SavePanier(panier);
    }

    public void AddMenu(int menuId, string nom, decimal prix, int quantite)
    {
        var panier = GetPanier();
        var itemKey = $"menu_{menuId}";

        var existingItem = panier.Items.FirstOrDefault(i => i.Key == itemKey);
        
        if (existingItem != null)
        {
            existingItem.Quantite += quantite;
            existingItem.SousTotal = existingItem.PrixUnitaire * existingItem.Quantite;
        }
        else
        {
            panier.Items.Add(new PanierItemViewModel
            {
                Key = itemKey,
                Type = "Menu",
                MenuId = menuId,
                Nom = nom,
                PrixUnitaire = prix,
                Quantite = quantite,
                SousTotal = prix * quantite
            });
        }

        SavePanier(panier);
    }

    public void AddComplement(int complementId, string nom, decimal prix, int quantite)
    {
        var panier = GetPanier();
        var itemKey = $"complement_{complementId}";

        var existingItem = panier.Items.FirstOrDefault(i => i.Key == itemKey);
        
        if (existingItem != null)
        {
            existingItem.Quantite += quantite;
            existingItem.SousTotal = existingItem.PrixUnitaire * existingItem.Quantite;
        }
        else
        {
            panier.Items.Add(new PanierItemViewModel
            {
                Key = itemKey,
                Type = "Complement",
                ComplementId = complementId,
                Nom = nom,
                PrixUnitaire = prix,
                Quantite = quantite,
                SousTotal = prix * quantite
            });
        }

        SavePanier(panier);
    }

    public void UpdateQuantite(string itemKey, int quantite)
    {
        var panier = GetPanier();
        var item = panier.Items.FirstOrDefault(i => i.Key == itemKey);

        if (item != null)
        {
            if (quantite <= 0)
            {
                panier.Items.Remove(item);
            }
            else
            {
                item.Quantite = quantite;
                item.SousTotal = item.PrixUnitaire * quantite;
            }

            SavePanier(panier);
        }
    }

    public void RemoveItem(string itemKey)
    {
        var panier = GetPanier();
        var item = panier.Items.FirstOrDefault(i => i.Key == itemKey);

        if (item != null)
        {
            panier.Items.Remove(item);
            SavePanier(panier);
        }
    }

    public void ClearPanier()
    {
        _sessionHelper.ClearPanier();
    }

    public decimal GetTotal()
    {
        var panier = GetPanier();
        return panier.Items.Sum(i => i.SousTotal);
    }
}