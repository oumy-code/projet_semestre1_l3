using brasilBugerC_.Models;

namespace brasilBugerC_.Services;

public interface IAuthenticationService
{
    Task<(bool Success, Client? Client, string Message)> LoginAsync(string login, string password);
    Task<(bool Success, int ClientId, string Message)> RegisterAsync(Client client, string password);
    Task<bool> IsLoginAvailableAsync(string login);
    Task<bool> IsEmailAvailableAsync(string email);
}
