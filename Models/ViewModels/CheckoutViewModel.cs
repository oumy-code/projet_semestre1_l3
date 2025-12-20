using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;
using System.ComponentModel.DataAnnotations;
using Microsoft.AspNetCore.Mvc.ModelBinding;

namespace brasilBugerC_.Models.ViewModels;


using Microsoft.AspNetCore.Mvc.ModelBinding; // Ajoute cet usage
// ... autres usings

public class CheckoutViewModel
{
    [BindNever] // Empêche le validateur de regarder cette propriété
    public PanierViewModel Panier { get; set; } = new();

    [Required(ErrorMessage = "Veuillez choisir un type de récupération")]
    public TypeRecuperation? TypeRecuperation { get; set; }

    public int? ZoneId { get; set; }

    public string? AdresseLivraison { get; set; }

    [Required(ErrorMessage = "Veuillez choisir un mode de paiement")]
    public ModePaiement? ModePaiement { get; set; }

    [BindNever] // Empêche la validation sur la liste des zones
    public IEnumerable<Zone> Zones { get; set; } = new List<Zone>();

    public decimal FraisLivraison { get; set; }

    public decimal Total => (Panier?.Total ?? 0) + FraisLivraison;

    // Utilise string pour éviter les problèmes de point/virgule lors du binding
    // Tu les convertiras en double dans le service si besoin
    public string? Latitude { get; set; }
    public string? Longitude { get; set; }
}