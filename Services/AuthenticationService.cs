using brasilBugerC_.Helpers;
using brasilBugerC_.Models;
using brasilBugerC_.Repositories;

namespace brasilBugerC_.Services;

public class AuthenticationService : IAuthenticationService
{
    private readonly IClientRepository _clientRepository;
    private readonly PasswordHasher _passwordHasher;

    public AuthenticationService(IClientRepository clientRepository, PasswordHasher passwordHasher)
    {
        _clientRepository = clientRepository;
        _passwordHasher = passwordHasher;
    }

   public async Task<(bool Success, Client? Client, string Message)> LoginAsync(string loginOrEmail, string password)
{
    if (string.IsNullOrWhiteSpace(loginOrEmail) || string.IsNullOrWhiteSpace(password))
        return (false, null, "Login et mot de passe requis");

    var client = await _clientRepository.GetByLoginAsync(loginOrEmail) 
                 ?? await _clientRepository.GetByEmailAsync(loginOrEmail);

    if (client == null)
    {
        Console.WriteLine($"Client non trouvé pour {loginOrEmail}");
        return (false, null, "Login ou mot de passe incorrect");
    }

    if (!_passwordHasher.VerifyPassword(password, client.MotDePasse))
    {
        Console.WriteLine($"Mot de passe incorrect pour {loginOrEmail}");
        return (false, null, "Login ou mot de passe incorrect");
    }

    Console.WriteLine($"Connexion réussie pour {loginOrEmail}");
    return (true, client, "Connexion réussie");
}

    public async Task<(bool Success, int ClientId, string Message)> RegisterAsync(Client client, string password)
    {
        if (string.IsNullOrWhiteSpace(client.Nom) || string.IsNullOrWhiteSpace(client.Prenom) ||
            string.IsNullOrWhiteSpace(client.Login) || string.IsNullOrWhiteSpace(client.Email) ||
            string.IsNullOrWhiteSpace(client.Telephone) || string.IsNullOrWhiteSpace(password) || password.Length < 6)
        {
            return (false, 0, "Tous les champs sont requis et le mot de passe doit contenir au moins 6 caractères");
        }

        if (await _clientRepository.ExistsAsync(client.Login, client.Email))
            return (false, 0, "Ce login ou email est déjà utilisé");

        client.MotDePasse = _passwordHasher.HashPassword(password);

        try
        {
            var clientId = await _clientRepository.CreateAsync(client);
            return (true, clientId, "Inscription réussie");
        }
        catch (Exception ex)
        {
            return (false, 0, $"Erreur lors de l'inscription: {ex.Message}");
        }
    }

    public Task<bool> IsLoginAvailableAsync(string login) => _clientRepository.GetByLoginAsync(login).ContinueWith(t => t.Result == null);
    public Task<bool> IsEmailAvailableAsync(string email) => _clientRepository.GetByEmailAsync(email).ContinueWith(t => t.Result == null);
}
