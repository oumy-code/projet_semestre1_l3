using System.ComponentModel.DataAnnotations;

namespace brasilBugerC_.Models.ViewModels;

public class RegisterViewModel
{
    [Required(ErrorMessage = "Le nom est requis")]
    [StringLength(100)]
    public string Nom { get; set; } = string.Empty;

    [Required(ErrorMessage = "Le prénom est requis")]
    [StringLength(100)]
    public string Prenom { get; set; } = string.Empty;

    [Required(ErrorMessage = "Le téléphone est requis")]
    [Phone(ErrorMessage = "Numéro de téléphone invalide")]
    public string Telephone { get; set; } = string.Empty;

    [Required(ErrorMessage = "L'email est requis")]
    [EmailAddress(ErrorMessage = "Email invalide")]
    public string Email { get; set; } = string.Empty;

    [Required(ErrorMessage = "Le login est requis")]
    [StringLength(50, MinimumLength = 3, ErrorMessage = "Le login doit contenir entre 3 et 50 caractères")]
    public string Login { get; set; } = string.Empty;

    [Required(ErrorMessage = "Le mot de passe est requis")]
    [StringLength(100, MinimumLength = 6, ErrorMessage = "Le mot de passe doit contenir au moins 6 caractères")]
    [DataType(DataType.Password)]
    public string MotDePasse { get; set; } = string.Empty;

    [Required(ErrorMessage = "Veuillez confirmer le mot de passe")]
    [DataType(DataType.Password)]
    [Compare("MotDePasse", ErrorMessage = "Les mots de passe ne correspondent pas")]
    public string ConfirmMotDePasse { get; set; } = string.Empty;
}