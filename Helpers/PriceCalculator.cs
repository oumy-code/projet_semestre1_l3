using brasilBugerC_.Models;

namespace brasilBugerC_.Helpers;

public class PriceCalculator
{
    public decimal CalculatePrixMenu(List<CompositionMenu> compositions)
    {
        decimal total = 0;
        
        foreach (var comp in compositions)
        {
            if (comp.Burger != null)
            {
                total += comp.Burger.Prix * comp.Quantite;
            }
            else if (comp.Complement != null)
            {
                total += comp.Complement.Prix * comp.Quantite;
            }
        }
        
        return total;
    }

    public decimal CalculateSousTotal(decimal prixUnitaire, int quantite)
    {
        return prixUnitaire * quantite;
    }

    public decimal CalculateMontantTotal(List<LigneCommande> lignes, decimal? fraisLivraison = null)
    {
        decimal total = lignes.Sum(l => l.SousTotal);
        
        if (fraisLivraison.HasValue)
        {
            total += fraisLivraison.Value;
        }
        
        return total;
    }
}