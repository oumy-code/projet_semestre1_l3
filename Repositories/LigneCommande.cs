using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;

namespace brasilBugerC_.Repositories;

public class LigneCommandeRepository : ILigneCommandeRepository
{
    private readonly DbConnectionFactory _connectionFactory;

    public LigneCommandeRepository(DbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task CreateAsync(LigneCommande ligne)
    {
        const string sql = @"
            INSERT INTO ligne_commande (id_commande, id_burger, id_menu, id_complement, quantite, prix_unitaire, sous_total)
            VALUES (@IdCommande, @IdBurger, @IdMenu, @IdComplement, @Quantite, @PrixUnitaire, @SousTotal)";

        using var connection = _connectionFactory.CreateConnection();
        await connection.ExecuteAsync(sql, ligne);
    }

    public async Task<IEnumerable<LigneCommande>> GetByCommandeIdAsync(int commandeId)
    {
        const string sql = @"
            SELECT 
                lc.id, lc.id_commande, lc.id_burger, lc.id_menu, lc.id_complement,
                lc.quantite, lc.prix_unitaire, lc.sous_total,
                b.id, b.nom, b.prix, b.image, b.archive, b.date_creation,
                m.id, m.nom, m.image, m.archive, m.date_creation,
                c.id, c.nom, c.type, c.prix, c.image, c.archive, c.date_creation
            FROM ligne_commande lc
            LEFT JOIN burger b ON lc.id_burger = b.id
            LEFT JOIN menu m ON lc.id_menu = m.id
            LEFT JOIN complement c ON lc.id_complement = c.id
            WHERE lc.id_commande = @CommandeId";

        using var connection = _connectionFactory.CreateConnection();
        
        var lignes = await connection.QueryAsync<LigneCommande, Burger?, Menu?, Complement?, LigneCommande>(
            sql,
            (ligne, burger, menu, complement) =>
            {
                ligne.Burger = burger;
                ligne.Menu = menu;
                ligne.Complement = complement;
                return ligne;
            },
            new { CommandeId = commandeId },
            splitOn: "id,id,id"
        );

        return lignes;
    }
}