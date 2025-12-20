using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public interface IClientRepository
{
    Task<Client?> GetByIdAsync(int id);
    Task<Client?> GetByLoginAsync(string login);
    Task<Client?> GetByEmailAsync(string email);
    Task<int> CreateAsync(Client client);
    Task<bool> UpdateAsync(Client client);
    Task<bool> ExistsAsync(string login, string email);
}